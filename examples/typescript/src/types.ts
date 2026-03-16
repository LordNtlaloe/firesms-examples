// types.ts — Fire SMS API types

export interface SendSMSOptions {
  apiKey: string;
  to: string;
  text: string;
}

export interface SendSMSSuccess {
  status: 'success';
  id: string;
}

export interface SendSMSError {
  status: 'error';
  error: string;
}

export type SendSMSResponse = SendSMSSuccess | SendSMSError;

export function isSuccess(res: SendSMSResponse): res is SendSMSSuccess {
  return res.status === 'success';
}
