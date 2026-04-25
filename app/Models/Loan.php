<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'member_id',
        'loan_date',
        'due_date',
        'returned_date',
        'return_date',
        'status',
        'fine_amount',
        'daily_fine_rate',
        'overdue_days',
        'total_fine',
        'fine_paid',
        'fine_calculated_at',
        'notes',
        'last_reminder_sent',
        'reminder_count',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'returned_date' => 'date',
        'fine_calculated_at' => 'datetime',
        'fine_paid' => 'boolean',
        'daily_fine_rate' => 'decimal:2',
        'total_fine' => 'decimal:2',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Calculate overdue charges for this loan
     */
    public function calculateOverdueCharges()
    {
        // Only calculate if loan is not returned
        if ($this->status === 'returned' || $this->returned_date) {
            return $this;
        }

        $dueDate = Carbon::parse($this->due_date);
        $today = Carbon::today();

        // Check if loan is overdue (more than 3 days past due date)
        $gracePeriod = (int) 3; // 3 days grace period - ensure it's an integer
        $overdueStartDate = $dueDate->copy()->addDays($gracePeriod);

        if ($today->isAfter($overdueStartDate)) {
            $this->overdue_days = (int) $overdueStartDate->diffInDays($today);
            $this->daily_fine_rate = $this->daily_fine_rate ?: 1.00; // Default 1 rupee per day
            $this->total_fine = (float) ($this->overdue_days * $this->daily_fine_rate);
            $this->fine_calculated_at = now();
            $this->save();
        }

        return $this;
    }

    /**
     * Check if loan is overdue
     */
    public function isOverdue()
    {
        if ($this->status === 'returned' || $this->returned_date) {
            return false;
        }

        $dueDate = Carbon::parse($this->due_date);
        $gracePeriod = (int) 3; // 3 days grace period - ensure it's an integer
        $overdueStartDate = $dueDate->copy()->addDays($gracePeriod);

        return Carbon::today()->isAfter($overdueStartDate);
    }

    /**
     * Get overdue days count
     */
    public function getOverdueDaysCount()
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        $dueDate = Carbon::parse($this->due_date);
        $gracePeriod = (int) 3; // Ensure it's an integer
        $overdueStartDate = $dueDate->copy()->addDays($gracePeriod);

        return (int) $overdueStartDate->diffInDays(Carbon::today());
    }

    /**
     * Mark fine as paid
     */
    public function markFineAsPaid()
    {
        $this->fine_paid = true;
        $this->save();
        return $this;
    }

    /**
     * Get formatted fine amount
     */
    public function getFormattedFineAttribute()
    {
        return '₹' . number_format($this->total_fine ?: 0, 2);
    }

    /**
     * Scope for overdue loans
     */
    public function scopeOverdue($query)
    {
        $overdueThreshold = Carbon::today()->subDays(3)->toDateString();

        return $query->where('status', '!=', 'returned')
            ->whereNull('returned_date')
            ->whereDate('due_date', '<', $overdueThreshold);
    }

    /**
     * Scope for loans with unpaid fines
     */
    public function scopeWithUnpaidFines($query)
    {
        return $query->where('total_fine', '>', 0)
            ->where('fine_paid', false);
    }
}
