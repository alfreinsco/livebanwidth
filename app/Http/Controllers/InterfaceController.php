<?php

namespace App\Http\Controllers;

use App\Helpers\MikroTikHelper;
use App\Jobs\SaveTrafficData;
use App\Models\RouterosAPI;
use Illuminate\Support\Facades\Auth;

class InterfaceController extends Controller
{
    public function index()
    {
        // Hanya return view tanpa fetch data
        // Data akan di-load via AJAX setelah halaman tampil
        return view('interface.index');
    }

    public function getInterfaces()
    {
        $credentials = MikroTikHelper::getCredentials();

        if (!$credentials) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan pilih atau tambahkan MikroTik terlebih dahulu.',
            ], 401);
        }

        $API = new RouterosAPI();
        $API->debug = false;
        $API->timeout = 5;
        $API->attempts = 2;

        try {
            if (!$API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal terhubung ke router MikroTik',
                ], 500);
            }

            $interfaces = $API->comm('/interface/print');
            $API->disconnect();

            return response()->json([
                'success' => true,
                'data' => $interfaces,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function traffic(string $interface)
    {
        $credentials = MikroTikHelper::getCredentials();

        if (!$credentials) {
            return redirect()->route('mikrotik.index')
                ->with('error', 'Silakan pilih atau tambahkan MikroTik terlebih dahulu.');
        }

        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            // Ambil detail interface
            $interfaceDetail = $API->comm('/interface/print', [
                '?name' => $interface,
            ]);

            $data = [
                'interface_name' => $interface,
                'interface_detail' => $interfaceDetail[0] ?? null,
            ];

            return view('interface.traffic', $data);
        }

        return redirect()->route('failed.view');
    }

    public function trafficApi(string $interface)
    {
        $credentials = MikroTikHelper::getCredentials();

        if (!$credentials) {
            return response()->json([
                'success' => false,
                'message' => 'MikroTik credentials not found',
            ], 401);
        }

        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            $getinterfacetraffic = $API->comm('/interface/monitor-traffic', [
                'interface' => $interface,
                'once'      => '',
            ]);

            $ftx = $getinterfacetraffic[0]['tx-bits-per-second'] ?? 0;
            $frx = $getinterfacetraffic[0]['rx-bits-per-second'] ?? 0;

            // Ambil mikrotik_id dari session atau user
            $mikrotikId = session('mikrotik_id') ?? (Auth::check() && Auth::user()->active_mikrotik_id ? Auth::user()->active_mikrotik_id : null);

            // Dispatch job untuk menyimpan data di background
            SaveTrafficData::dispatch($interface, $credentials['ip'], $credentials['user'], $credentials['password'], $mikrotikId)
                ->onQueue('traffic');

            return response()->json([
                'success' => true,
                'data'    => [
                    'tx' => (float) $ftx,
                    'rx' => (float) $frx,
                    'tx_mbps' => round((float) $ftx / 1000000, 2),
                    'rx_mbps' => round((float) $frx / 1000000, 2),
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Connection Failed',
        ], 500);
    }

    public function collectAllTraffic()
    {
        $credentials = MikroTikHelper::getCredentials();

        if (!$credentials) {
            return response()->json([
                'success' => false,
                'message' => 'MikroTik credentials not found',
            ], 401);
        }

        $API = new RouterosAPI();
        $API->debug = false;
        $API->timeout = 5;
        $API->attempts = 2;

        if (!$API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke router',
            ], 500);
        }

        // Ambil mikrotik_id dari session atau user
        $mikrotikId = session('mikrotik_id') ?? (Auth::check() && Auth::user()->active_mikrotik_id ? Auth::user()->active_mikrotik_id : null);

        // Ambil semua interface yang running
        $allInterfaces = $API->comm('/interface/print');
        $interfaces = [];
            foreach ($allInterfaces as $if) {
                if (isset($if['running']) && $if['running'] == 'true' && isset($if['name'])) {
                    $interfaces[] = $if['name'];
                    // Dispatch job untuk setiap interface
                    SaveTrafficData::dispatch($if['name'], $credentials['ip'], $credentials['user'], $credentials['password'], $mikrotikId)
                        ->onQueue('traffic');
                }
            }

        $API->disconnect();

        return response()->json([
            'success' => true,
            'message' => 'Traffic data collection queued for ' . count($interfaces) . ' interface(s)',
            'interfaces' => $interfaces,
        ]);
    }
}


