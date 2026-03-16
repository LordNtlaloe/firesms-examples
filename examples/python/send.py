# send.py — send a single SMS

from firesms import send_sms, FireSMSError

try:
    result = send_sms(
        to='27821234567',
        text='Hello from Fire SMS via Python! 🔥',
    )
    print('✅ SMS sent successfully!')
    print('   Message ID:', result['id'])

except FireSMSError as e:
    print('❌ API error:', e)

except Exception as e:
    print('❌ Request failed:', e)
