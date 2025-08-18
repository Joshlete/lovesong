<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {email : The email of the user to make admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a user an admin by their email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }
        
        if ($user->is_admin) {
            // Still verify them if they're not verified yet
            if (!$user->hasVerifiedEmail()) {
                $user->update(['email_verified_at' => now()]);
                $this->info("User '{$user->name}' ({$email}) is already an admin, but has now been verified.");
                return 0;
            }
            $this->info("User '{$user->name}' ({$email}) is already an admin and verified.");
            return 0;
        }
        
        $user->update([
            'is_admin' => true,
            'email_verified_at' => now(), // Auto-verify admin users
        ]);
        
        $this->info("User '{$user->name}' ({$email}) has been made an admin and verified.");
        return 0;
    }
}
