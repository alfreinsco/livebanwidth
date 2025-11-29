<?php

namespace App\Console\Commands;

use App\Jobs\SaveTrafficData;
use App\Models\RouterosAPI;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;

class CollectTrafficData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'traffic:collect {--interface=*} {--ip=} {--user=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect traffic data from MikroTik router and save to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ip = $this->option('ip');
        $user = $this->option('user');
        $password = $this->option('password');
        $interfaces = $this->option('interface');

        if (!$ip || !$user || !$password) {
            $this->error('Router credentials required. Use --ip, --user, --password options.');
            $this->info('Example: php artisan traffic:collect --ip=192.168.1.1 --user=admin --password=admin');
            return 1;
        }

        $API = new RouterosAPI();
        $API->debug = false;
        $API->timeout = 5;
        $API->attempts = 2;

        if (!$API->connect($ip, $user, $password)) {
            $this->error('Failed to connect to router');
            return 1;
        }

        // Jika interface tidak ditentukan, ambil semua interface yang running
        if (empty($interfaces)) {
            $allInterfaces = $API->comm('/interface/print');
            $interfaces = [];
            foreach ($allInterfaces as $if) {
                if (isset($if['running']) && $if['running'] == 'true' && isset($if['name'])) {
                    $interfaces[] = $if['name'];
                }
            }
        }

        $this->info("Collecting traffic data for " . count($interfaces) . " interface(s)...");

        foreach ($interfaces as $interface) {
            SaveTrafficData::dispatch($interface, $ip, $user, $password)
                ->onQueue('traffic');
            $this->line("Queued traffic collection for: {$interface}");
        }

        $API->disconnect();
        $this->info('Traffic data collection queued successfully!');
        return 0;
    }
}
