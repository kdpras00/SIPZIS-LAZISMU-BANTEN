<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use App\Models\Payment;

class SyncCampaignCollectedAmounts extends Command
{
    
    protected $signature = 'campaigns:sync-collected-amounts';

    
    protected $description = 'Sync collected_amount in campaigns table based on actual payments';

    
    public function handle()
    {
        $this->info('Syncing collected_amount for all campaigns...');

        $campaigns = Campaign::all();
        $updated = 0;

        foreach ($campaigns as $campaign) {
            $collectedAmount = 0;

            
            if ($campaign->program_id) {
                $collectedAmount = Payment::where('program_id', $campaign->program_id)
                    ->where('status', 'completed')
                    ->where('created_at', '>=', $campaign->created_at)
                    ->sum('paid_amount');
            } else {
                $collectedAmount = Payment::where('program_category', $campaign->program_category)
                    ->whereNotNull('program_category')
                    ->where('status', 'completed')
                    ->where('created_at', '>=', $campaign->created_at)
                    ->sum('paid_amount');
            }

            
            $campaign->update(['collected_amount' => $collectedAmount]);
            $updated++;

            $this->line("Updated campaign '{$campaign->title}': Rp " . number_format($collectedAmount, 0, ',', '.'));
        }

        $this->info("Successfully synced {$updated} campaigns!");
        return 0;
    }
}
