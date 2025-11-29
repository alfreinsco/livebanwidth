<?php

namespace App\Http\Controllers;

use App\Helpers\MikroTikHelper;
use App\Models\Report;
use App\Models\RouterosAPI;
use Illuminate\Support\Facades\Schema;

class LegacyDashboardController extends Controller
{
    public function index()
    {
        // Load halaman dashboard dengan cepat tanpa data berat
        // Data akan di-load via AJAX setelah halaman tampil
        return view('dashboard.index');
    }

    public function getDashboardData()
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
        $API->timeout = 5; // Set timeout lebih pendek
        $API->attempts = 2; // Kurangi jumlah attempt

        try {
            if (!$API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal terhubung ke router MikroTik',
                ], 500);
            }

            // Ambil data penting saja dengan timeout
            set_time_limit(20); // Set max execution time

            // Ambil data secara bertahap, mulai dari yang penting
            $resource = $API->comm('/system/resource/print');
            $identity = $API->comm('/system/identity/print');
            $routerboard = $API->comm('/system/routerboard/print');

            // Data ringan lainnya
            $hotspotactive = $API->comm('/ip/hotspot/active/print');
            $secret = $API->comm('/ppp/secret/print');
            $secretactive = $API->comm('/ppp/active/print');

            // Data yang lebih berat - ambil dengan limit atau skip jika terlalu lama
            $interface = [];
            $hotspotusers = [];
            $useractive = [];

            try {
                $interface = $API->comm('/interface/print');
            } catch (\Exception $e) {
                // Skip jika error
            }

            try {
                $hotspotusers = $API->comm('/ip/hotspot/user/print');
            } catch (\Exception $e) {
                // Skip jika error
            }

            try {
                $useractive = $API->comm('/user/active/print');
            } catch (\Exception $e) {
                // Skip jika error
            }

            // Data report untuk traffic logs
            $totalReports = 0;
            $reports24h = 0;
            try {
                $totalReports = Report::count();
                $reports24h = Report::where('time', '>=', now()->subDay())->count();
            } catch (\Exception $e) {
                // Jika tabel belum ada atau error, set ke 0
                $totalReports = 0;
                $reports24h = 0;
            }

            // Hitung interface yang running
            $interfaceRunning = 0;
            if (is_array($interface)) {
                foreach ($interface as $if) {
                    if (isset($if['running']) && $if['running'] == 'true') {
                        $interfaceRunning++;
                    }
                }
            }

            $API->disconnect();

            return response()->json([
                'success' => true,
                'data'    => [
                    'totalsecret'       => count($secret),
                    'totalhotspot'      => count($hotspotactive),
                    'totalhotspotusers' => count($hotspotusers),
                    'hotspotactive'     => count($hotspotactive),
                    'secretactive'      => count($secretactive),
                    'totalinterface'    => count($interface),
                    'interfacerunning'  => $interfaceRunning,
                    'totaluseractive'   => count($useractive),
                    'cpu'               => $resource[0]['cpu-load'] ?? null,
                    'uptime'            => $resource[0]['uptime'] ?? null,
                    'version'           => $resource[0]['version'] ?? null,
                    'interface'         => array_slice($interface, 0, 5), // Limit untuk performa
                    'boardname'         => $resource[0]['board-name'] ?? null,
                    'freememory'        => $resource[0]['free-memory'] ?? null,
                    'freehdd'           => $resource[0]['free-hdd-space'] ?? null,
                    'model'             => $routerboard[0]['model'] ?? null,
                    'identity'          => $identity[0]['name'] ?? null,
                    'totalreports'      => $totalReports,
                    'reports24h'        => $reports24h,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function cpu()
    {
        $credentials = MikroTikHelper::getCredentials();
        if (!$credentials) {
            return view('failed');
        }

        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            $cpu = $API->comm('/system/resource/print');

            $data = [
                'cpu' => $cpu[0]['cpu-load'] ?? null,
            ];

            return view('realtime.cpu', $data);
        }

        return view('failed');
    }

    public function uptime()
    {
        $credentials = MikroTikHelper::getCredentials();
        if (!$credentials) {
            return view('failed');
        }

        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            $uptime = $API->comm('/system/resource/print');

            $data = [
                'uptime' => $uptime[0]['uptime'] ?? null,
            ];

            return view('realtime.uptime', $data);
        }

        return view('failed');
    }

    public function traffic(string $traffic)
    {
        $credentials = MikroTikHelper::getCredentials();
        if (!$credentials) {
            return view('failed');
        }

        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            $trafficData = $API->comm('/interface/monitor-traffic', [
                'interface' => $traffic,
                'once'      => '',
            ]);

            $rx = $trafficData[0]['rx-bits-per-second'] ?? 0;
            $tx = $trafficData[0]['tx-bits-per-second'] ?? 0;

            $data = [
                'rx' => $rx,
                'tx' => $tx,
            ];

            return view('realtime.traffic', $data);
        }

        return view('failed');
    }

    public function apiTraffic(string $traffic)
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
            $trafficData = $API->comm('/interface/monitor-traffic', [
                'interface' => $traffic,
                'once'      => '',
            ]);

            $rx = $trafficData[0]['rx-bits-per-second'] ?? 0;
            $tx = $trafficData[0]['tx-bits-per-second'] ?? 0;

            return response()->json([
                'success' => true,
                'message' => 'Data traffic berhasil di ambil',
                'data'    => [
                    'rx' => $rx,
                    'tx' => $tx,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data traffic',
            'data'    => null,
        ]);
    }

    public function load()
    {
        $data = Report::orderBy('created_at', 'desc')->limit(2)->get();

        return view('realtime.load', compact('data'));
    }
}


