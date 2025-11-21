<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Loan;

class OverdueReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;

    /**
     * Create a new message instance.
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Overdue Book Reminder - ' . $this->loan->book->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.overdue-reminder',
            with: [
                'memberName' => $this->loan->member->name,
                'bookTitle' => $this->loan->book->title,
                'author' => $this->loan->book->author,
                'loanDate' => $this->loan->loan_date,
                'dueDate' => $this->loan->due_date,
                'daysOverdue' => now()->diffInDays($this->loan->due_date),
                'lateFee' => $this->calculateLateFee(),
            ]
        );
    }

    /**
     * Calculate late fee based on days overdue
     */
    private function calculateLateFee()
    {
        $daysOverdue = now()->diffInDays($this->loan->due_date);
        $feePerDay = 1.00; // Default late fee per day
        return $daysOverdue * $feePerDay;
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
