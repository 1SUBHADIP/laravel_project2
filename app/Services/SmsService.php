<?php

namespace App\Services;

use App\Models\Loan;
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
            // Simulate SMS sending
            Log::info("SMS sent to {$phoneNumber}: {$message}");

            // In production, you would use something like:
            // $twilio = new Client($accountSid, $authToken);
            // $twilio->messages->create($phoneNumber, [
            //     'from' => '+1234567890',
            //     'body' => $message
            // ]);

            return true;
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
