# Fire SMS — TypeScript Example App

A typed TypeScript application demonstrating how to send SMS messages using the [Fire SMS API](https://firesms.vercel.app). Uses discriminated union types for safe response handling and can be run directly with `ts-node` or compiled to JavaScript.

---

## Prerequisites

- **Node.js 18+** — [nodejs.org](https://nodejs.org)
- **npm** — bundled with Node.js
- A **Fire SMS API key** — [firesms.vercel.app](https://firesms.vercel.app)

Verify:
```bash
node --version   # v18+
npm --version    # 9+
```

---

## Setup

### 1. Clone and navigate

```bash
git clone https://github.com/your-org/firesms-examples.git
cd firesms-examples/typescript
```

### 2. Install dependencies

```bash
npm install
```

This installs `typescript`, `ts-node`, and `@types/node`.

### 3. Set your API key

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

---

## Usage

### Send a single SMS (with ts-node, no build step)

```bash
npm start
```

Output:
```
✅ SMS sent successfully!
   Message ID: abc123
```

### Send to multiple recipients

```bash
npm run bulk
```

Output:
```
Sending 3 messages...

✅ 27821234567 — ID: abc123
✅ 27831234567 — ID: def456
❌ 27841234567 — Invalid number format

Done. 2 sent, 1 failed.
```

### Build and run compiled JavaScript

```bash
npm run build          # Compiles src/ → dist/
npm run start:compiled # Runs dist/send.js
npm run bulk:compiled  # Runs dist/bulk.js
```

---

## How it works

### Types (`src/types.ts`)

The API response is modelled as a discriminated union, giving you full type safety:

```ts
interface SendSMSSuccess {
  status: 'success';
  id: string;
}

interface SendSMSError {
  status: 'error';
  error: string;
}

type SendSMSResponse = SendSMSSuccess | SendSMSError;

// Type guard — narrows the union in if/else blocks
function isSuccess(res: SendSMSResponse): res is SendSMSSuccess {
  return res.status === 'success';
}
```

### The client (`src/firesms.ts`)

```ts
export async function sendSMS(options: SendSMSOptions): Promise<SendSMSResponse> {
  const response = await fetch('https://firesms.vercel.app/api/v1/send', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      api_key: options.apiKey,
      to: options.to,
      text: options.text,
    }),
  });

  if (!response.ok) {
    throw new Error(`HTTP error: ${response.status}`);
  }

  return response.json() as Promise<SendSMSResponse>;
}
```

### Handling the response

Because `SendSMSResponse` is a discriminated union, TypeScript will enforce that you handle both cases:

```ts
const result = await sendSMS({ apiKey, to, text });

if (isSuccess(result)) {
  console.log('Sent! ID:', result.id);    // ✅ TypeScript knows result.id exists
} else {
  console.error('Failed:', result.error); // ✅ TypeScript knows result.error exists
}
```

### API request fields

| Field | Type | Description |
|---|---|---|
| `api_key` | `string` | Your Fire SMS API key |
| `to` | `string` | Recipient in E.164 format (e.g. `27821234567`) |
| `text` | `string` | The message body |

---

## Project structure

```
typescript/
├── src/
│   ├── types.ts      # API types and type guards
│   ├── firesms.ts    # Typed Fire SMS client
│   ├── send.ts       # Send a single SMS
│   └── bulk.ts       # Send to multiple recipients
├── dist/             # Compiled output (after npm run build)
├── package.json
├── tsconfig.json
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
