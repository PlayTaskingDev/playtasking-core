<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Stancl\Tenancy\Concerns\HasATenantsOption;
use Stancl\Tenancy\Concerns\TenantAwareCommand;

class UpdateUsersRanking extends Command
{
    use TenantAwareCommand, HasATenantsOption;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rankings:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hourly update for the tags ranking';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::where('points','>',0)->orderBy('points','desc')->get();

        foreach ($users as $key => $user) {
            $user->ranking = $key + 1;
            $user->save();
        }

        return Command::SUCCESS;
    }
}
