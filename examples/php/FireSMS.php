<?php

// FireSMS.php — reusable Fire SMS client

class FireSMSException extends RuntimeException {}

class FireSMS
{
    private string $apiKey;
    private string $baseUrl = 'https://firesms.vercel.app/api/v1';

    public function __construct(string $apiKey = null)
    {
        $key = $apiKey ?? getenv('FIRESMS_API_KEY');

        if (!$key) {
            throw new InvalidArgumentException(
                'API key is required. Pass it to the constructor or set FIRESMS_API_KEY.'
            );
        }

        $this->apiKey = $key;
    }

    /**
     * Send an SMS message.
     *
     * @param  string $to   Recipient phone number in E.164 format (e.g. '27821234567')
     * @param  string $text Message body
     * @return array        Response data with 'status' and 'id'
     *
     * @throws FireSMSException  On API-level error
     * @throws RuntimeException  On HTTP or network error
     */
    public function send(string $to, string $text): array
    {
        $payload = json_encode([
            'api_key' => $this->apiKey,
            'to'      => $to,
            'text'    => $text,
        ]);

        $context = stream_context_create([
            'http' => [
                'method'        => 'POST',
                'header'        => "Content-Type: application/json\r\n",
                'content'       => $payload,
                'timeout'       => 10,
                'ignore_errors' => true,
            ],
        ]);

        $raw = file_get_contents("{$this->baseUrl}/send", false, $context);

        if ($raw === false) {
            throw new RuntimeException('Request failed: could not reach the Fire SMS API.');
        }

        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid JSON response from Fire SMS API.');
        }

        if (($data['status'] ?? '') === 'error') {
            throw new FireSMSException($data['error'] ?? 'Unknown API error');
        }

        return $data;
    }
}
