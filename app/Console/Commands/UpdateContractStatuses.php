<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hospital;

class UpdateContractStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update contract statuses for all hospitals based on current dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hospitals = Hospital::whereNotNull('contract_start_date')
                            ->whereNotNull('contract_end_date')
                            ->get();

        $updated = 0;
        $expired = 0;
        $expiringSoon = 0;

        foreach ($hospitals as $hospital) {
            $oldStatus = $hospital->contract_status;
            $hospital->updateContractStatus();
            
            if ($oldStatus !== $hospital->contract_status) {
                $updated++;
                
                if ($hospital->contract_status === 'expired') {
                    $expired++;
                } elseif ($hospital->contract_status === 'active' && $hospital->getContractDaysRemaining() <= 30) {
                    $expiringSoon++;
                }
            }
        }

        $this->info("Contract statuses updated successfully!");
        $this->info("Total hospitals processed: " . $hospitals->count());
        $this->info("Statuses updated: " . $updated);
        $this->info("Contracts expired: " . $expired);
        $this->info("Contracts expiring soon: " . $expiringSoon);

        return 0;
    }
}