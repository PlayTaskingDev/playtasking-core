<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApiClient;

class GenerateApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:token {client} 
        {--tenants= : Tenant ID for the API client}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $tenantIds = explode(',', $this->option('tenants'));

        $client = ApiClient::createWithToken([
            'name' => $this->argument('client'),
            'company' => $this->argument('client'),
            'active' => true,
            'description' => 'Cliente API de Cantabria' 
        ], $tenantIds);


        if (!$client) {
            $this->error('Cliente no encontrado.');
            return self::FAILURE;
        }

        $this->info('=======================================');
        $this->info('Cliente : '.$client['client']->name);
        $this->info('Tenants  : '.$client['client']->tenants->pluck('id')->implode(', '));
        $this->info('Token   :');
        $this->line($client['token']);
        $this->info('=======================================');

        return self::SUCCESS;
    }
}
