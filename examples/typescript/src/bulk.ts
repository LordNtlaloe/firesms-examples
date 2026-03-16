// bulk.ts — send SMS to multiple recipients

import { sendSMS } from './firesms.js';
import { isSuccess, SendSMSOptions } from './types.js';

const API_KEY = process.env.FIRESMS_API_KEY;

if (!API_KEY) {
  console.error('Error: FIRESMS_API_KEY environment variable is not set.');
  process.exit(1);
}

const recipients: Omit<SendSMSOptions, 'apiKey'>[] = [
  { to: '27821234567', text: 'Hi Alice, this is a message from Fire SMS!' },
  { to: '27831234567', text: 'Hi Bob, this is a message from Fire SMS!' },
  { to: '27841234567', text: 'Hi Carol, this is a message from Fire SMS!' },
];

console.log(`Sending ${recipients.length} messages...\n`);

const results = await Promise.allSettled(
  recipients.map(({ to, text }) => sendSMS({ apiKey: API_KEY!, to, text }))
);

let passed = 0;
let failed = 0;

results.forEach((result, i) => {
  const { to } = recipients[i];
  if (result.status === 'fulfilled' && isSuccess(result.value)) {
    console.log(`✅ ${to} — ID: ${result.value.id}`);
    passed++;
  } else {
    const reason =
      result.status === 'rejected'
        ? result.reason?.message
        : result.value.error;
    console.error(`❌ ${to} — ${reason ?? 'Unknown error'}`);
    failed++;
  }
});

console.log(`\nDone. ${passed} sent, ${failed} failed.`);
