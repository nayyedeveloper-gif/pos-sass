<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LicenseService;

class GenerateLicenseKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'license:generate {machine_id : The Machine ID from customer screen} {--type=LIFETIME : License Type (LIFETIME or SUBSCRIPTION)} {--days=30 : Duration in days for subscription}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a license key for a specific machine ID';

    /**
     * Execute the console command.
     */
    public function handle(LicenseService $licenseService)
    {
        $machineId = $this->argument('machine_id');
        $type = strtoupper($this->option('type'));
        $days = (int) $this->option('days');

        if ($type !== 'LIFETIME' && $type !== 'SUBSCRIPTION') {
            $this->error('Invalid type. Use LIFETIME or SUBSCRIPTION.');
            return;
        }

        $key = $licenseService->generateLicense($machineId, $type, $days);

        $this->info('--------------------------------------------------');
        $this->info(' LICENSE KEY GENERATED SUCCESSFULLY');
        $this->info('--------------------------------------------------');
        $this->line(" Machine ID : $machineId");
        $this->line(" Type       : $type");
        if ($type === 'SUBSCRIPTION') {
            $this->line(" Duration   : $days Days");
            $this->line(" Expires    : " . now()->addDays($days)->format('Y-m-d'));
        }
        $this->info('--------------------------------------------------');
        $this->comment(" Key: $key");
        $this->info('--------------------------------------------------');
    }
}
