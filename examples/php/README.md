# Fire SMS — PHP Example App

A PHP application demonstrating how to send SMS messages using the [Fire SMS API](https://firesms.vercel.app). Uses a reusable `FireSMS` class with a custom exception for clean error handling. Includes a minimal `.env` loader — no Composer or external dependencies required.

---

## Prerequisites

- **PHP 8.0+** — [php.net](https://php.net)
- `allow_url_fopen` enabled in `php.ini` (enabled by default)
- A **Fire SMS API key** — [firesms.vercel.app](https://firesms.vercel.app)

Verify:
```bash
php --version   # PHP 8.0+
```

Check `allow_url_fopen` is on:
```bash
php -r "echo ini_get('allow_url_fopen') ? 'allow_url_fopen: ON' : 'allow_url_fopen: OFF';"
```

---

## Setup

### 1. Clone and navigate

```bash
git clone https://github.com/your-org/firesms-examples.git
cd firesms-examples/examples/php
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
php send.php
```

Output:
```
✅ SMS sent successfully!
   Message ID: abc123
```

### Send to multiple recipients

```bash
php bulk.php
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

### The client (`FireSMS.php`)

The `FireSMS` class wraps the API and raises exceptions for both configuration problems and API-level errors:

```php
$client = new FireSMS(); // reads FIRESMS_API_KEY from environment

$result = $client->send(
    to:   '27821234567',
    text: 'Hello from Fire SMS!'
);

echo 'Sent! ID: ' . $result['id'];
```

You can also pass the key directly:

```php
$client = new FireSMS(apiKey: 'your_api_key_here');
```

### Under the hood

The client uses PHP's built-in `file_get_contents` with a stream context to make the POST request — no Composer or curl extension required:

```php
$context = stream_context_create([
    'http' => [
        'method'        => 'POST',
        'header'        => "Content-Type: application/json\r\n",
        'content'       => json_encode(['api_key' => $key, 'to' => $to, 'text' => $text]),
        'timeout'       => 10,
        'ignore_errors' => true,  // Prevents PHP warnings on 4xx/5xx responses
    ],
]);

$raw = file_get_contents('https://firesms.vercel.app/api/v1/send', false, $context);
```

### Error handling

Three exception types cover all failure scenarios:

```php
try {
    $client = new FireSMS();
    $result = $client->send(to: '27821234567', text: 'Hello!');
    echo 'Sent! ID: ' . $result['id'];

} catch (InvalidArgumentException $e) {
    // Missing API key
    echo 'Config error: ' . $e->getMessage();

} catch (FireSMSException $e) {
    // API returned status: error (e.g. invalid number)
    echo 'API error: ' . $e->getMessage();

} catch (RuntimeException $e) {
    // Network failure, timeout, invalid JSON response
    echo 'Request failed: ' . $e->getMessage();
}
```

### API request fields

| Field | Type | Description |
|---|---|---|
| `api_key` | string | Your Fire SMS API key |
| `to` | string | Recipient in E.164 format (e.g. `27821234567`) |
| `text` | string | The message body |

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
php/
├── dotenv.php      # Minimal .env loader (no Composer needed)
├── FireSMS.php     # Reusable Fire SMS client class
├── send.php        # Send a single SMS
├── bulk.php        # Send to multiple recipients
├── .env.example    # Copy to .env and add your key
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
