// firesms.js — reusable Fire SMS client

const BASE_URL = 'https://firesms.vercel.app/api/v1';

export async function sendSMS({ apiKey, to, text }) {
  const response = await fetch(`${BASE_URL}/send`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ api_key: apiKey, to, text }),
  });

  if (!response.ok) {
    throw new Error(`HTTP error: ${response.status}`);
  }

  return response.json();
}
