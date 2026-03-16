// firesms.ts — typed Fire SMS client

import { SendSMSOptions, SendSMSResponse } from './types.js';

const BASE_URL = 'https://firesms.vercel.app/api/v1';

export async function sendSMS(options: SendSMSOptions): Promise<SendSMSResponse> {
  const response = await fetch(`${BASE_URL}/send`, {
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
