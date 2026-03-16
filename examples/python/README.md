# Fire SMS — Python Example App

A Python application demonstrating how to send SMS messages using the [Fire SMS API](https://firesms.vercel.app). Uses the `requests` library and a custom exception class for clean error handling.

---

## Prerequisites

- **Python 3.7+** — [python.org](https://python.org)
- **pip** — bundled with Python
- A **Fire SMS API key** — [firesms.vercel.app](https://firesms.vercel.app)

Verify:
```bash
python --version   # Python 3.7+
pip --version
```

---

## Setup

### 1. Clone and navigate

```bash
git clone https://github.com/your-org/firesms-examples.git
cd firesms-examples/python
```

### 2. Create a virtual environment

```bash
python -m venv venv
```

Activate it:

**macOS / Linux:**
```bash
source venv/bin/activate
```

**Windows (Command Prompt):**
```cmd
venv\Scripts\activate
```

**Windows (PowerShell):**
```powershell
.\venv\Scripts\Activate.ps1
```

You should see `(venv)` in your terminal prompt.

### 3. Install dependencies

```bash
pip install -r requirements.txt
```

### 4. Set your API key

Create a `.env` file in this directory:

```bash
FIRESMS_API_KEY=your_api_key_here
```

> ⚠️ Never commit your `.env` file. It's listed in `.gitignore` by default.

---

## Usage

### Send a single SMS

```bash
python send.py
```

Output:
```
✅ SMS sent successfully!
   Message ID: abc123
```

### Send to multiple recipients

```bash
python bulk.py
```

Output:
```
Sending 3 messages...

✅ 27821234567 — ID: abc123
✅ 27831234567 — ID: def456
❌ 27841234567 — API error: Invalid number format

Done. 2 sent, 1 failed.
```

---

## How it works

### The client (`firesms.py`)

The `send_sms()` function wraps the Fire SMS API and raises a custom `FireSMSError` for API-level errors:

```python
def send_sms(to: str, text: str, api_key: str = None) -> dict:
    key = api_key or os.environ.get('FIRESMS_API_KEY')

    if not key:
        raise ValueError('API key is required.')

    response = requests.post(
        'https://firesms.vercel.app/api/v1/send',
        json={'api_key': key, 'to': to, 'text': text},
        timeout=10,
    )

    response.raise_for_status()   # Raises HTTPError for 4xx/5xx
    data = response.json()

    if data.get('status') == 'error':
        raise FireSMSError(data.get('error', 'Unknown error'))

    return data
```

### Error handling

Two types of errors are handled separately:

```python
try:
    result = send_sms(to='27821234567', text='Hello!')
    print('Sent! ID:', result['id'])

except FireSMSError as e:
    # API returned status: error (e.g. invalid number)
    print('API error:', e)

except requests.HTTPError as e:
    # Non-2xx HTTP response (e.g. 401 Unauthorized, 500 Server Error)
    print('HTTP error:', e)

except Exception as e:
    # Network timeout, DNS failure, etc.
    print('Request failed:', e)
```

### API request fields

| Field | Type | Description |
|---|---|---|
| `api_key` | str | Your Fire SMS API key |
| `to` | str | Recipient in E.164 format (e.g. `27821234567`) |
| `text` | str | The message body |

### API response

**Success:**
```json
{
  "status": "success",
  "id": "abc123"
}
```

**Failure:**
```json
{
  "status": "error",
  "error": "Invalid phone number"
}
```

---

## Project structure

```
python/
├── firesms.py          # Reusable Fire SMS client
├── send.py             # Send a single SMS
├── bulk.py             # Send to multiple recipients
├── requirements.txt
└── README.md
```

---

## Phone number format

All numbers must be in **E.164 format** — country code followed by the number, no `+`, spaces, or dashes:

| Country | Example |
|---|---|
| South Africa | `27821234567` |
| United States | `12025551234` |
| United Kingdom | `447911123456` |

---

## Docs

Full API reference: [firesms.vercel.app/docs](https://firesms.vercel.app/docs)
