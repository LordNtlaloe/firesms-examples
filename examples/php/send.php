<?php

// send.php — send a single SMS

require_once __DIR__ . '/FireSMS.php';

try {
    $client = new FireSMS();

    $result = $client->send(
        to:   '27821234567',
        text: 'Hello from Fire SMS via PHP! 🔥'
    );

    echo '✅ SMS sent successfully!' . PHP_EOL;
    echo '   Message ID: ' . $result['id'] . PHP_EOL;

} catch (InvalidArgumentException $e) {
    echo '❌ Configuration error: ' . $e->getMessage() . PHP_EOL;
} catch (FireSMSException $e) {
    echo '❌ API error: ' . $e->getMessage() . PHP_EOL;
} catch (RuntimeException $e) {
    echo '❌ Request failed: ' . $e->getMessage() . PHP_EOL;
}
