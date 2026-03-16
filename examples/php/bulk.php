<?php

// bulk.php — send SMS to multiple recipients

require_once __DIR__ . '/FireSMS.php';

$recipients = [
    ['to' => '27821234567', 'text' => 'Hi Alice, this is a message from Fire SMS!'],
    ['to' => '27831234567', 'text' => 'Hi Bob, this is a message from Fire SMS!'],
    ['to' => '27841234567', 'text' => 'Hi Carol, this is a message from Fire SMS!'],
];

echo 'Sending ' . count($recipients) . ' messages...' . PHP_EOL . PHP_EOL;

$passed = 0;
$failed = 0;

try {
    $client = new FireSMS();
} catch (InvalidArgumentException $e) {
    echo '❌ Configuration error: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}

foreach ($recipients as $recipient) {
    $to = $recipient['to'];

    try {
        $result = $client->send(to: $to, text: $recipient['text']);
        echo "✅ {$to} — ID: {$result['id']}" . PHP_EOL;
        $passed++;
    } catch (FireSMSException $e) {
        echo "❌ {$to} — API error: {$e->getMessage()}" . PHP_EOL;
        $failed++;
    } catch (RuntimeException $e) {
        echo "❌ {$to} — Request failed: {$e->getMessage()}" . PHP_EOL;
        $failed++;
    }
}

echo PHP_EOL . "Done. {$passed} sent, {$failed} failed." . PHP_EOL;
