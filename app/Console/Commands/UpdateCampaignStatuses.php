<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateCampaignStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaigns:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically mark campaigns as completed when they expire or reach their target amount';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating campaign statuses...');

        // Find all published campaigns
        $publishedCampaigns = Campaign::where('status', 'published')->get();

        $completedCount = 0;
        $errorCount = 0;

        foreach ($publishedCampaigns as $campaign) {
            try {
                // Check if campaign should be completed (expired OR target reached)
                $shouldComplete = false;
                $reason = '';

                if ($campaign->isExpired()) {
                    $shouldComplete = true;
                    $reason = 'expired';
                } elseif ($campaign->isTargetReached()) {
                    $shouldComplete = true;
                    $reason = 'target reached';
                }

                if ($shouldComplete) {
                    $campaignTitle = $campaign->title;
                    
                    // Mark as completed
                    $campaign->update(['status' => 'completed']);
                    $completedCount++;

                    $this->info("Marked campaign '{$campaignTitle}' as completed (Reason: {$reason}).");
                    
                    // Log the update
                    Log::info("Campaign automatically marked as completed", [
                        'campaign_id' => $campaign->id,
                        'campaign_title' => $campaignTitle,
                        'reason' => $reason,
                        'end_date' => $campaign->end_date,
                        'target_amount' => $campaign->target_amount,
                        'collected_amount' => $campaign->collected_amount
                    ]);
                }
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("Failed to update campaign '{$campaign->title}': " . $e->getMessage());
                Log::error("Failed to automatically update campaign status", [
                    'campaign_id' => $campaign->id,
                    'campaign_title' => $campaign->title,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("Completed! Marked {$completedCount} campaigns as completed.");
        
        if ($errorCount > 0) {
            $this->warn("Encountered errors with {$errorCount} campaigns.");
        }

        // Also log for monitoring purposes
        Log::info("Campaign status update job completed", [
            'total_checked' => $publishedCampaigns->count(),
            'completed_count' => $completedCount,
            'error_count' => $errorCount
        ]);

        return Command::SUCCESS;
    }
}