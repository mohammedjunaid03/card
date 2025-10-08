<?php

namespace App\Console\Commands;

use App\Models\HealthCard;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckHealthCardExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'healthcard:check-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expiring health cards and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expiring health cards...');

        // Check cards expiring in 30 days
        $expiringIn30Days = HealthCard::with('user')
            ->where('status', 'active')
            ->where('expiry_date', '<=', Carbon::now()->addDays(30))
            ->where('expiry_date', '>', Carbon::now())
            ->get();

        foreach ($expiringIn30Days as $card) {
            // Check if notification already sent
            $existingNotification = Notification::where('recipient_type', 'App\Models\User')
                ->where('recipient_id', $card->user_id)
                ->where('type', 'health_card_expiry_30_days')
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->first();

            if (!$existingNotification) {
                Notification::create([
                    'recipient_type' => 'App\Models\User',
                    'recipient_id' => $card->user_id,
                    'type' => 'health_card_expiry_30_days',
                    'title' => 'Health Card Expiring Soon',
                    'message' => "Your health card ({$card->card_number}) will expire on {$card->expiry_date->format('d-m-Y')}. Please renew it to continue using healthcare services.",
                    'data' => [
                        'card_number' => $card->card_number,
                        'expiry_date' => $card->expiry_date->format('d-m-Y'),
                        'days_remaining' => $card->expiry_date->diffInDays(Carbon::now())
                    ]
                ]);

                $this->line("Notification sent to {$card->user->name} for card {$card->card_number}");
            }
        }

        // Check cards expiring in 7 days
        $expiringIn7Days = HealthCard::with('user')
            ->where('status', 'active')
            ->where('expiry_date', '<=', Carbon::now()->addDays(7))
            ->where('expiry_date', '>', Carbon::now())
            ->get();

        foreach ($expiringIn7Days as $card) {
            // Check if notification already sent
            $existingNotification = Notification::where('recipient_type', 'App\Models\User')
                ->where('recipient_id', $card->user_id)
                ->where('type', 'health_card_expiry_7_days')
                ->where('created_at', '>=', Carbon::now()->subDays(3))
                ->first();

            if (!$existingNotification) {
                Notification::create([
                    'recipient_type' => 'App\Models\User',
                    'recipient_id' => $card->user_id,
                    'type' => 'health_card_expiry_7_days',
                    'title' => 'Health Card Expiring Very Soon',
                    'message' => "URGENT: Your health card ({$card->card_number}) will expire on {$card->expiry_date->format('d-m-Y')}. Please renew immediately to avoid service interruption.",
                    'data' => [
                        'card_number' => $card->card_number,
                        'expiry_date' => $card->expiry_date->format('d-m-Y'),
                        'days_remaining' => $card->expiry_date->diffInDays(Carbon::now())
                    ]
                ]);

                $this->line("Urgent notification sent to {$card->user->name} for card {$card->card_number}");
            }
        }

        // Mark expired cards
        $expiredCards = HealthCard::where('status', 'active')
            ->where('expiry_date', '<=', Carbon::now())
            ->get();

        foreach ($expiredCards as $card) {
            $card->update(['status' => 'expired']);
            
            // Send expiry notification
            Notification::create([
                'recipient_type' => 'App\Models\User',
                'recipient_id' => $card->user_id,
                'type' => 'health_card_expired',
                'title' => 'Health Card Expired',
                'message' => "Your health card ({$card->card_number}) has expired on {$card->expiry_date->format('d-m-Y')}. Please renew it to continue using healthcare services.",
                'data' => [
                    'card_number' => $card->card_number,
                    'expiry_date' => $card->expiry_date->format('d-m-Y')
                ]
            ]);

            $this->line("Marked card {$card->card_number} as expired");
        }

        $this->info('Health card expiry check completed.');
    }
}