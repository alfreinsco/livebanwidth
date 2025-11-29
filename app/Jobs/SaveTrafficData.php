<?php

namespace App\Jobs;

use App\Models\RouterosAPI;
use App\Models\Traffic;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SaveTrafficData implements ShouldQueue
{
    use Queueable;

    public $timeout = 30;
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $interfaceName,
        public string $ip,
        public string $user,
        public string $password,
        public ?int $mikrotikId = null
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info("SaveTrafficData job started for interface: {$this->interfaceName}, MikroTik ID: {$this->mikrotikId}");

            $API = new RouterosAPI();
            $API->debug = false;
            $API->timeout = 5;
            $API->attempts = 2;

            if (!$API->connect($this->ip, $this->user, $this->password)) {
                $errorMsg = "Failed to connect to router for traffic data: {$this->interfaceName} (IP: {$this->ip}, User: {$this->user})";
                Log::warning($errorMsg);
                throw new \Exception($errorMsg);
            }

            Log::info("Connected to router, fetching traffic data for interface: {$this->interfaceName}");

            $trafficData = $API->comm('/interface/monitor-traffic', [
                'interface' => $this->interfaceName,
                'once'      => '',
            ]);

            $tx = (int) ($trafficData[0]['tx-bits-per-second'] ?? 0);
            $rx = (int) ($trafficData[0]['rx-bits-per-second'] ?? 0);
            $txMbps = round($tx / 1000000, 2);
            $rxMbps = round($rx / 1000000, 2);

            Log::info("Traffic data retrieved - TX: {$tx} bps, RX: {$rx} bps");

            $traffic = Traffic::create([
                'interface_name' => $this->interfaceName,
                'tx_bits'        => $tx,
                'rx_bits'        => $rx,
                'tx_mbps'        => $txMbps,
                'rx_mbps'        => $rxMbps,
                'mikrotik_id'    => $this->mikrotikId,
            ]);

            Log::info("Traffic data saved successfully. ID: {$traffic->id}");

            $API->disconnect();
        } catch (\Exception $e) {
            Log::error("Error saving traffic data for {$this->interfaceName}: " . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
