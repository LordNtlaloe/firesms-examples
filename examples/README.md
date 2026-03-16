# Fire SMS — Examples

This repository contains working example applications for the [Fire SMS API](https://firesms.vercel.app). Each example is self-contained, fully documented, and shows you everything from installation to sending your first message.

---

## What's inside

```
examples/
├── nodejs/       Node.js — no dependencies, native fetch
├── typescript/   TypeScript — fully typed client with type guards
├── python/       Python — requests library with custom exceptions
└── php/          PHP — no Composer, built-in file_get_contents
```

Each folder contains its own README with step-by-step setup instructions.

---

## Before you start

You need a Fire SMS API key.

1. Create a free account at [firesms.vercel.app](https://firesms.vercel.app)
2. Go to **Dashboard → API Keys → Create API Key**
3. Copy your key immediately — it won't be shown again

> ⚠️ Keep your API key out of source control. Every example in this repo reads it from an environment variable called `FIRESMS_API_KEY`.

---

## Getting started

Clone the repo and navigate into the example you want to run:

```bash
git clone https://github.com/your-org/firesms-examples.git
cd firesms-examples/examples/nodejs   # or typescript, python, php
```

Copy the example env file and add your key:

```bash
cp .env.example .env
```

Then open `.env` and replace `your_api_key_here` with your actual key:

```bash
FIRESMS_API_KEY=your_api_key_here
```

Then follow the README inside that folder.

---

## The API

Every example talks to the same endpoint.

**Send an SMS**
```
POST https://firesms.vercel.app/api/v1/send
Content-Type: application/json
```

**Request body**
```json
{
  "api_key": "your_api_key_here",
  "to": "27821234567",
  "text": "Hello from Fire SMS!"
}
```

| Field | Type | Description |
|---|---|---|
| `api_key` | string | Your Fire SMS API key |
| `to` | string | Recipient phone number in E.164 format |
| `text` | string | Message body — up to 160 characters for a standard SMS |

**Success**
```json
{
  "status": "success",
  "id": "abc123"
}
```

**Error**
```json
{
  "status": "error",
  "error": "Invalid phone number"
}
```

---

## Phone number format

Fire SMS uses **E.164 format**: country code followed by the subscriber number, with no `+`, spaces, brackets, or dashes.

| Country | Local format | E.164 |
|---|---|---|
| South Africa | 082 123 4567 | `27821234567` |
| Lesotho | 5012 3456 | `26650123456` |
| Botswana | 71 234 567 | `26771234567` |

The rule is simple — drop the leading `0` from the local number and prepend the country code.

---

## Running the examples

### Node.js

No install needed. Requires Node.js 18 or higher.

```bash
cd examples/nodejs
node send.js       # single SMS
node bulk.js       # multiple recipients
```

→ [Node.js README](./nodejs/README.md)

---

### TypeScript

Install dev dependencies first, then run with `ts-node` — no build step required.

```bash
cd examples/typescript
npm install
npm start          # single SMS
npm run bulk       # multiple recipients
```

→ [TypeScript README](./typescript/README.md)

---

### Python

Create a virtual environment, install `requests`, then run.

```bash
cd examples/python
python -m venv venv
source venv/bin/activate    # Windows: venv\Scripts\activate
pip install -r requirements.txt
python send.py              # single SMS
python bulk.py              # multiple recipients
```

→ [Python README](./python/README.md)

---

### PHP

No Composer needed. Requires PHP 8.0 or higher.

```bash
cd examples/php
php send.php       # single SMS
php bulk.php       # multiple recipients
```

→ [PHP README](./php/README.md)

---

## Bulk sending

Every example ships with a `bulk` script that sends to a list of recipients and reports results per number without stopping on a single failure.

```
Sending 3 messages...

✅ 27821234567 — ID: abc123
✅ 26650123456 — ID: def456
❌ 26771234567 — API error: Invalid number format

Done. 2 sent, 1 failed.
```

---

## Error handling

Every example separates three categories of failure so you always know what went wrong:

| Category | What it means |
|---|---|
| Configuration error | `FIRESMS_API_KEY` is missing or empty |
| API error | Fire SMS rejected the request — bad key, invalid number, etc. |
| Network error | The request never reached the API — timeout, no connection |

---

## Full API reference

[firesms.vercel.app/docs](https://firesms.vercel.app/docs)
