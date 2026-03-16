# Fire SMS — Code Examples

A collection of ready-to-run example applications showing how to integrate the [Fire SMS API](https://firesms.vercel.app) into your project. Each example covers sending a single SMS, sending to multiple recipients, error handling, and environment-based API key management.

---

## Examples

| Language | Directory | Requirements |
|---|---|---|
| Node.js | [`examples/nodejs`](./examples/nodejs) | Node.js 18+ |
| TypeScript | [`examples/typescript`](./examples/typescript) | Node.js 18+, npm |
| Python | [`examples/python`](./examples/python) | Python 3.7+, pip |
| PHP | [`examples/php`](./examples/php) | PHP 8.0+ |

---

## Prerequisites

Before running any example you need a **Fire SMS API key**.

1. Sign up at [firesms.vercel.app](https://firesms.vercel.app)
2. Go to **Dashboard → API Keys → Create API Key**
3. Copy your key — you won't be able to see it again

> ⚠️ Never commit your API key to source control. All examples read it from an environment variable.

---

## Quick start

### 1. Clone the repository

```bash
git clone https://github.com/your-org/firesms-examples.git
cd firesms-examples
```

### 2. Set your API key

Every example reads from the same environment variable — set it once and any example will pick it up.

**macOS / Linux:**
```bash
export FIRESMS_API_KEY=your_api_key_here
```

**Windows (Command Prompt):**
```cmd
set FIRESMS_API_KEY=your_api_key_here
```

**Windows (PowerShell):**
```powershell
$env:FIRESMS_API_KEY="your_api_key_here"
```

### 3. Pick a language and follow its README

```bash
cd examples/nodejs      # or typescript, python, php
```

Each directory has its own README with full setup and usage instructions.

---

## The API

All examples call the same single endpoint.

### Endpoint

```
POST https://firesms.vercel.app/api/v1/send
```

### Request body

```json
{
  "api_key": "your_api_key_here",
  "to": "27821234567",
  "text": "Hello from Fire SMS!"
}
```

| Field | Type | Required | Description |
|---|---|---|---|
| `api_key` | string | ✅ | Your Fire SMS API key |
| `to` | string | ✅ | Recipient phone number in E.164 format |
| `text` | string | ✅ | The message body (max 160 characters for a single SMS) |

### Success response

```json
{
  "status": "success",
  "id": "abc123"
}
```

### Error response

```json
{
  "status": "error",
  "error": "Invalid phone number"
}
```

---

## Phone number format

All numbers must use **E.164 format** — country code followed by the subscriber number, with no `+`, spaces, brackets, or dashes.

| Country | Raw number | E.164 format |
|---|---|---|
| South Africa | 082 123 4567 | `27821234567` |
| United States | (202) 555-1234 | `12025551234` |
| United Kingdom | 07911 123456 | `447911123456` |
| Kenya | 0712 345678 | `254712345678` |

**Formula:** remove the leading `0` from the local number and prepend the country code.

---

## Language guides

### Node.js

No dependencies required — uses Node's built-in `fetch` (Node 18+).

```bash
cd examples/nodejs
export FIRESMS_API_KEY=your_api_key_here
node send.js
```

Key file: `firesms.js` — a single exported `sendSMS()` function.

→ [Full Node.js guide](./examples/nodejs/README.md)

---

### TypeScript

Fully typed with discriminated union responses and a `isSuccess()` type guard. Run directly with `ts-node` or compile to JavaScript.

```bash
cd examples/typescript
npm install
export FIRESMS_API_KEY=your_api_key_here
npm start
```

Key files: `src/types.ts` for the API types, `src/firesms.ts` for the client.

→ [Full TypeScript guide](./examples/typescript/README.md)

---

### Python

Uses the `requests` library with a custom `FireSMSException` for clean error handling.

```bash
cd examples/python
python -m venv venv && source venv/bin/activate
pip install -r requirements.txt
export FIRESMS_API_KEY=your_api_key_here
python send.py
```

Key file: `firesms.py` — a `send_sms()` function that raises on errors.

→ [Full Python guide](./examples/python/README.md)

---

### PHP

No Composer or extensions required — uses PHP's built-in `file_get_contents` with a stream context.

```bash
cd examples/php
export FIRESMS_API_KEY=your_api_key_here
php send.php
```

Key file: `FireSMS.php` — a `FireSMS` class with a `send()` method.

→ [Full PHP guide](./examples/php/README.md)

---

## Sending to multiple recipients

Every example includes a `bulk` script that sends to a list of recipients and reports per-number results without stopping on a single failure.

| Language | Command |
|---|---|
| Node.js | `node bulk.js` |
| TypeScript | `npm run bulk` |
| Python | `python bulk.py` |
| PHP | `php bulk.php` |

Example output:
```
Sending 3 messages...

✅ 27821234567 — ID: abc123
✅ 27831234567 — ID: def456
❌ 27841234567 — API error: Invalid number format

Done. 2 sent, 1 failed.
```

---

## Error handling

Every example handles three categories of failure:

| Error type | Cause | Example |
|---|---|---|
| Configuration error | Missing API key | `FIRESMS_API_KEY` not set |
| API error | Fire SMS rejected the request | Invalid number, bad key |
| Network error | Could not reach the API | Timeout, DNS failure |

---

## Project structure

```
firesms-examples/
├── README.md               ← you are here
└── examples/
    ├── nodejs/
    │   ├── firesms.js      # Fire SMS client
    │   ├── send.js         # Send a single SMS
    │   ├── bulk.js         # Send to multiple recipients
    │   ├── package.json
    │   └── README.md
    ├── typescript/
    │   ├── src/
    │   │   ├── types.ts    # API types and type guards
    │   │   ├── firesms.ts  # Typed Fire SMS client
    │   │   ├── send.ts     # Send a single SMS
    │   │   └── bulk.ts     # Send to multiple recipients
    │   ├── tsconfig.json
    │   ├── package.json
    │   └── README.md
    ├── python/
    │   ├── firesms.py      # Fire SMS client
    │   ├── send.py         # Send a single SMS
    │   ├── bulk.py         # Send to multiple recipients
    │   ├── requirements.txt
    │   └── README.md
    └── php/
        ├── FireSMS.php     # Fire SMS client class
        ├── send.php        # Send a single SMS
        ├── bulk.php        # Send to multiple recipients
        └── README.md
```

---

## Docs

Full API reference: [firesms.vercel.app/docs](https://firesms.vercel.app/docs)
