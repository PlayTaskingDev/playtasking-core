<?php

namespace App\Console\Commands;

use App\Models\AwardCode;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RestoreTestInteractions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset';

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
        DB::table('award_user')->truncate();
        DB::table('user_interactions')->truncate();

        $award_codes = AwardCode::where('active',true)->get();

        foreach ($award_codes as $key => $award_code) {
            $award_code->active = false;
            $award_code->user_id = NULL;
            $award_code->save();
        }

        // Reset not admin users
        $users = User::withoutRole('admin')->get();
        foreach ($users as $key => $user) {
            $user->delete();
        }

        // Reset admin users
        $users = User::role('admin')->get();
        foreach ($users as $key => $user) {
            $user->ranking = NULL;
            $user->points = 0;
            $user->save();
        }

        return Command::SUCCESS;
    }
}
