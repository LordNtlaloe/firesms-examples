# Fire SMS — Node.js Example App

A simple Node.js application demonstrating how to send SMS messages using the [Fire SMS API](https://firesms.vercel.app). No external dependencies — uses Node's built-in `fetch`.

---

## Prerequisites

- **Node.js 18+** — download at [nodejs.org](https://nodejs.org)
- A **Fire SMS API key** — get one at [firesms.vercel.app](https://firesms.vercel.app)

Verify your Node version:
```bash
node --version
# Should print v18.0.0 or higher
```

---

## Setup

### 1. Clone the repository

```bash
git clone https://github.com/your-org/firesms-examples.git
cd firesms-examples/nodejs
```

### 2. Set your API key

Create a `.env` file in this directory:

```bash
FIRESMS_API_KEY=your_api_key_here
```

> ⚠️ Never commit your `.env` file. It's listed in `.gitignore` by default.

---

## Usage

### Send a single SMS

```bash
node send.js
```

Output:
```
✅ SMS sent successfully!
   Message ID: abc123
```

### Send to multiple recipients

```bash
node bulk.js
```

Output:
```
Sending 3 messages...

✅ 27821234567 — ID: abc123
✅ 27831234567 — ID: def456
❌ 27841234567 — Invalid number format

Done. 2 sent, 1 failed.
```

---

## How it works

### The client (`firesms.js`)

All API calls go through a single reusable function:

```js
export async function sendSMS({ apiKey, to, text }) {
  const response = await fetch('https://firesms.vercel.app/api/v1/send', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ api_key: apiKey, to, text }),
  });

  if (!response.ok) {
    throw new Error(`HTTP error: ${response.status}`);
  }

  return response.json();
}
```

### The API request

| Field | Type | Description |
|---|---|---|
| `api_key` | string | Your Fire SMS API key |
| `to` | string | Recipient phone number in E.164 format (e.g. `27821234567`) |
| `text` | string | The message body |

### The API response

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

### Bulk sending

For multiple recipients, use `Promise.allSettled()` so that one failure doesn't cancel the rest:

```js
const results = await Promise.allSettled(
  recipients.map(({ to, text }) => sendSMS({ apiKey, to, text }))
);
```

---

## Project structure

```
nodejs/
├── firesms.js      # Reusable Fire SMS client
├── send.js         # Send a single SMS
├── bulk.js         # Send to multiple recipients
├── package.json
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
