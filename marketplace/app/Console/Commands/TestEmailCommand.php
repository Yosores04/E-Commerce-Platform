<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a welcome notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'john.doe@customer.com';
        
        $this->info("Testing email configuration...");
        $this->info("Sending test email to: $email");
        
        try {
            // Find user
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("User not found with email: $email");
                $this->info("Available test users:");
                $this->info("- john.doe@customer.com");
                $this->info("- admin@marketplace.com");
                $this->info("- techstore@vendor.com");
                return;
            }
            
            // Send notification
            $user->notify(new WelcomeNotification());
            
            $this->info("✓ Email sent successfully!");
            $this->info("Check your inbox at: $email");
            
        } catch (\Exception $e) {
            $this->error("✗ Failed to send email:");
            $this->error($e->getMessage());
        }
    }
}
