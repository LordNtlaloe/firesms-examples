# bulk.py — send SMS to multiple recipients

from firesms import send_sms, FireSMSError

recipients = [
    {'to': '27821234567', 'text': 'Hi Alice, this is a message from Fire SMS!'},
    {'to': '27831234567', 'text': 'Hi Bob, this is a message from Fire SMS!'},
    {'to': '27841234567', 'text': 'Hi Carol, this is a message from Fire SMS!'},
]

print(f'Sending {len(recipients)} messages...\n')

passed = 0
failed = 0

for recipient in recipients:
    to = recipient['to']
    try:
        result = send_sms(to=to, text=recipient['text'])
        print(f"✅ {to} — ID: {result['id']}")
        passed += 1
    except FireSMSError as e:
        print(f'❌ {to} — API error: {e}')
        failed += 1
    except Exception as e:
        print(f'❌ {to} — Request failed: {e}')
        failed += 1

print(f'\nDone. {passed} sent, {failed} failed.')
