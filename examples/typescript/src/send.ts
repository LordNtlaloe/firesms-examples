// send.ts — send a single SMS

import { sendSMS } from './firesms.js';
import { isSuccess } from './types.js';

const API_KEY = process.env.FIRESMS_API_KEY;

if (!API_KEY) {
  console.error('Error: FIRESMS_API_KEY environment variable is not set.');
  process.exit(1);
}

try {
  const result = await sendSMS({
    apiKey: API_KEY,
    to: '27821234567',
    text: 'Hello from Fire SMS via TypeScript! 🔥',
  });

  if (isSuccess(result)) {
    console.log('✅ SMS sent successfully!');
    console.log('   Message ID:', result.id);
  } else {
    console.error('❌ Failed to send SMS:', result.error);
  }
} catch (err: unknown) {
  const message = err instanceof Error ? err.message : String(err);
  console.error('❌ Request failed:', message);
}
