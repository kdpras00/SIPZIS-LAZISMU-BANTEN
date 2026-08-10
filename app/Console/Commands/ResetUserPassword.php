<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword extends Command
{
    
    protected $signature = 'app:reset-user-password {email} {password?}';

    
    protected $description = 'Reset password for a specific user by email';

    
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password') ?? 'password'; 

        
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        
        $user->password = Hash::make($password);
        $user->save();

        $this->info("Password for user {$email} has been reset successfully.");
        $this->info("New password: {$password}");

        return 0;
    }
}
