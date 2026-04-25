<?php

namespace App\Services;

use App\Models\Loan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
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
        // Basic phone number validation
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        return strlen($cleaned) >= 10;
    }
}
