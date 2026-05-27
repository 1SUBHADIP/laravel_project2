<?php

namespace App\Services;

use App\Models\Loan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Normalize a phone number for SMS delivery.
     */
    public function normalizePhoneNumber(string $phoneNumber): string
    {
        $cleaned = preg_replace('/[^0-9+]/', '', trim($phoneNumber));

        if ($cleaned === null) {
            return '';
        }

        if (str_starts_with($cleaned, '00')) {
            return '+' . substr($cleaned, 2);
        }

        if (str_starts_with($cleaned, '+')) {
            return '+' . preg_replace('/\D+/', '', substr($cleaned, 1));
        }

        return preg_replace('/\D+/', '', $cleaned) ?: '';
    }

    /**
     * Format a phone number for providers that require E.164.
     */
    public function formatPhoneNumberForSms(string $phoneNumber): string
    {
        $normalized = $this->normalizePhoneNumber($phoneNumber);
        if ($normalized === '') {
            return '';
        }

        if (str_starts_with($normalized, '+')) {
            return $normalized;
        }

        $countryCode = (string) config('services.sms.default_country_code', '+1');
        $countryCode = $this->normalizePhoneNumber($countryCode);

        if ($countryCode === '') {
            $countryCode = '+1';
        }

        if (!str_starts_with($countryCode, '+')) {
            $countryCode = '+' . ltrim($countryCode, '+');
        }

        return $countryCode . $normalized;
    }

    /**
     * Send SMS reminder for overdue book
     */
    public function sendOverdueReminder(Loan $loan)
    {
        $member = $loan->member;
        $book = $loan->book;
        $daysOverdue = now()->diffInDays($loan->due_date);

        // Check if member has a phone number
        if (empty($member->phone)) {
            Log::warning("Cannot send SMS to member {$member->id}: No phone number");
            return false;
        }

        $message = $this->formatOverdueMessage($member->name, $book->title, $daysOverdue);

        // For now, we'll simulate SMS sending
        // In production, you would integrate with an SMS service like Twilio, Nexmo, etc.
        return $this->sendSms($member->phone, $message);
    }

    /**
     * Format the overdue message
     */
    private function formatOverdueMessage($memberName, $bookTitle, $daysOverdue)
    {
        return "Hi {$memberName}, your book '{$bookTitle}' is {$daysOverdue} days overdue. Please return it to CCLMS Library soon to avoid additional late fees. Thank you!";
    }

    /**
     * Send SMS (simulated for now)
     * In production, integrate with SMS gateway like Twilio
     */
    private function sendSms($phoneNumber, $message)
    {
        try {
            $phoneNumber = $this->formatPhoneNumberForSms((string) $phoneNumber);

            if ($phoneNumber === '' || !preg_match('/^\+[1-9]\d{7,14}$/', $phoneNumber)) {
                Log::warning('SMS delivery skipped because the phone number is invalid or not in E.164 format.', [
                    'phone' => $phoneNumber,
                ]);

                return false;
            }

            $twilioSid = config('services.twilio.sid');
            $twilioToken = config('services.twilio.token');
            $twilioFrom = config('services.twilio.from');

            if (empty($twilioSid) || empty($twilioToken) || empty($twilioFrom)) {
                Log::warning('SMS delivery is not configured. Set TWILIO_SID, TWILIO_AUTH_TOKEN, and TWILIO_FROM_NUMBER.');
                return false;
            }

            $response = Http::withBasicAuth($twilioSid, $twilioToken)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$twilioSid}/Messages.json", [
                    'From' => $twilioFrom,
                    'To' => $phoneNumber,
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                Log::info("SMS sent to {$phoneNumber}");
                return true;
            }

            Log::error('Failed to send SMS', [
                'phone' => $phoneNumber,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error("Failed to send SMS to {$phoneNumber}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate phone number format
     */
    public function isValidPhoneNumber($phoneNumber)
    {
        $formatted = $this->formatPhoneNumberForSms((string) $phoneNumber);

        return (bool) preg_match('/^\+[1-9]\d{7,14}$/', $formatted);
    }

    /**
     * Public helper to send a plain SMS message (wraps internal sendSms)
     */
    public function sendSmsMessage(string $phoneNumber, string $message): bool
    {
        return $this->sendSms($phoneNumber, $message);
    }
}
