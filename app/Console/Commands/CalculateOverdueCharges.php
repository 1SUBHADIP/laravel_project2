<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;

class CalculateOverdueCharges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:calculate-overdue-charges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate overdue charges for all active loans';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting overdue charges calculation...');

        // Get all overdue loans
        $overdueLoans = Loan::overdue()->get();

        if ($overdueLoans->isEmpty()) {
            $this->info('No overdue loans found.');
            return;
        }

        $updatedCount = 0;

        foreach ($overdueLoans as $loan) {
            $previousFine = $loan->total_fine;
            $loan->calculateOverdueCharges();

            if ($loan->total_fine != $previousFine) {
                $updatedCount++;
                $this->line("Updated fine for loan #{$loan->id}: ₹{$loan->total_fine} ({$loan->overdue_days} days overdue)");
            }
        }

        $this->info("Processed {$overdueLoans->count()} overdue loans, updated {$updatedCount} fines.");

        return Command::SUCCESS;
    }
}
