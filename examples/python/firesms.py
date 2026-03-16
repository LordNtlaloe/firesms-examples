# firesms.py — reusable Fire SMS client

import os
import requests
from dotenv import load_dotenv

load_dotenv()

BASE_URL = 'https://firesms.vercel.app/api/v1'


class FireSMSError(Exception):
    """Raised when the Fire SMS API returns an error response."""
    pass


def send_sms(to: str, text: str, api_key: str = None) -> dict:
    """
    Send an SMS via Fire SMS.

    Args:
        to:      Recipient phone number in E.164 format (e.g. '27821234567')
        text:    Message body
        api_key: Fire SMS API key. Falls back to FIRESMS_API_KEY env var.

    Returns:
        dict with 'status' and 'id' on success.

    Raises:
        FireSMSError:  API returned an error response.
        ValueError:    API key is missing.
        requests.HTTPError: Non-2xx HTTP response.
    """
    key = api_key or os.environ.get('FIRESMS_API_KEY')

    if not key:
        raise ValueError(
            'API key is required. Pass api_key= or set FIRESMS_API_KEY.'
        )

    response = requests.post(
        f'{BASE_URL}/send',
        json={'api_key': key, 'to': to, 'text': text},
        timeout=10,
    )

    response.raise_for_status()
    data = response.json()

    if data.get('status') == 'error':
        raise FireSMSError(data.get('error', 'Unknown error'))

    return data
