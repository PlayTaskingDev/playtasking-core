<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class VerifiyUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $users = User::whereNull('email_verified_at')->get();

        foreach ($users as $key => $user) {
            $user->email_verified_at = $now;
            $user->save();
        }
        
        return Command::SUCCESS;
    }
}
