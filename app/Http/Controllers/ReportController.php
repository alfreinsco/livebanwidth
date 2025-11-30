<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Traffic;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        return view('report.traffic-up');
    }

    public function search(Request $request)
    {
        // Hanya return view tanpa fetch data
        // Data akan di-load via AJAX setelah halaman tampil
        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');

        return view('report.search-traffic', compact('tgl_awal', 'tgl_akhir'));
    }

    public function searchData(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');
        $perPage = $request->input('per_page', 15); // Default 15 items per page

        try {
            if (! $tgl_awal || ! $tgl_akhir) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal awal dan akhir harus diisi',
                ], 400);
            }

            // Ambil data dari traffic_logs (Traffic model)
            $trafficData = Traffic::with('mikrotik')
                ->whereDate('created_at', '>=', $tgl_awal)
                ->whereDate('created_at', '<=', $tgl_akhir)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    // Tambahkan field untuk sorting
                    $item->sort_time = $item->created_at;

                    return $item;
                });

            // Ambil data dari data table (Report model) juga jika ada
            $reportData = Report::with('mikrotik')
                ->where('time', '>=', $tgl_awal.' 00:00:00')
                ->where('time', '<=', $tgl_akhir.' 23:59:59')
                ->orderBy('time', 'desc')
                ->get()
                ->map(function ($item) {
                    // Tambahkan field untuk sorting
                    $item->sort_time = $item->time;

                    return $item;
                });

            // Gabungkan data dan sort berdasarkan waktu
            $allData = $trafficData->concat($reportData)
                ->sortByDesc('sort_time')
                ->values();

            // Buat pagination manual
            $currentPage = (int) $request->input('page', 1);
            $currentItems = $allData->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $paginatedData = new LengthAwarePaginator(
                $currentItems,
                $allData->count(),
                $perPage,
                $currentPage,
                [
                    'path' => $request->url(),
                    'pageName' => 'page',
                    'query' => $request->query(), // Preserve query parameters
                ]
            );

            // Format data untuk response
            $formattedData = $currentItems->map(function ($item) {
                // Tentukan type berdasarkan apakah item adalah Traffic atau Report
                $type = isset($item->interface_name) ? 'traffic' : 'report';

                return [
                    'id' => $item->id,
                    'type' => $type,
                    'interface_name' => $item->interface_name ?? null,
                    'tx_mbps' => $item->tx_mbps !== null ? (float) $item->tx_mbps : null,
                    'rx_mbps' => $item->rx_mbps !== null ? (float) $item->rx_mbps : null,
                    'tx_bits' => $item->tx_bits ?? null,
                    'rx_bits' => $item->rx_bits ?? null,
                    'text' => $item->text ?? null,
                    'created_at' => $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : null,
                    'time' => $item->time ? (is_string($item->time) ? $item->time : (is_object($item->time) ? $item->time->format('Y-m-d H:i:s') : $item->time)) : null,
                    'mikrotik' => $item->mikrotik ? [
                        'id' => $item->mikrotik->id,
                        'name' => $item->mikrotik->name,
                        'ip_address' => $item->mikrotik->ip_address,
                    ] : null,
                ];
            });

            // Generate pagination URLs
            $paginationLinks = [];
            $baseUrl = $request->url();
            $queryParams = $request->except('page');

            // Previous page
            if ($paginatedData->currentPage() > 1) {
                $prevParams = array_merge($queryParams, ['page' => $paginatedData->currentPage() - 1]);
                $paginationLinks['prev'] = $baseUrl.'?'.http_build_query($prevParams);
            } else {
                $paginationLinks['prev'] = null;
            }

            // Next page
            if ($paginatedData->currentPage() < $paginatedData->lastPage()) {
                $nextParams = array_merge($queryParams, ['page' => $paginatedData->currentPage() + 1]);
                $paginationLinks['next'] = $baseUrl.'?'.http_build_query($nextParams);
            } else {
                $paginationLinks['next'] = null;
            }

            return response()->json([
                'success' => true,
                'data' => $formattedData,
                'pagination' => [
                    'current_page' => $paginatedData->currentPage(),
                    'last_page' => $paginatedData->lastPage(),
                    'per_page' => $paginatedData->perPage(),
                    'total' => $paginatedData->total(),
                    'from' => $paginatedData->firstItem(),
                    'to' => $paginatedData->lastItem(),
                    'prev_url' => $paginationLinks['prev'],
                    'next_url' => $paginationLinks['next'],
                ],
                'view_tgl' => "List data Mulai tanggal: $tgl_awal, Sampai tanggal: $tgl_akhir (Total: ".number_format($allData->count()).' records)',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: '.$e->getMessage(),
            ], 500);
        }
    }

    public function load()
    {
        try {
            $data = Report::with('mikrotik')
                ->orderBy('time', 'desc')
                ->limit(20)
                ->get();

            return view('realtime.load', compact('data'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error loading data: '.$e->getMessage(),
            ], 500);
        }
    }

    public function up()
    {
        $mikrotikId = null;
        if (Auth::check() && Auth::user()->active_mikrotik_id) {
            $mikrotikId = Auth::user()->active_mikrotik_id;
        }

        $post = new Report;
        $post->text = '<font color=`ff0000`>Traffic Internet Melebihi Dari 50 Mbps</font>';
        $post->mikrotik_id = $mikrotikId;
        $post->save();

        return response()->json($post, 200);
    }

    public function down()
    {
        $mikrotikId = null;
        if (Auth::check() && Auth::user()->active_mikrotik_id) {
            $mikrotikId = Auth::user()->active_mikrotik_id;
        }

        $post = new Report;
        $post->text = 'Traffic Internet Stabil, Kurang Dari 50 Mbps';
        $post->mikrotik_id = $mikrotikId;
        $post->save();

        return response()->json($post, 200);
    }

    public function deleteTrafficLog(Request $request, $id)
    {
        try {
            // Untuk DELETE method, ambil type dari query parameter
            $type = $request->query('type', 'traffic'); // 'traffic' or 'report'

            if ($type === 'traffic') {
                $log = Traffic::findOrFail($id);
                $log->delete();
            } else {
                $log = Report::findOrFail($id);
                $log->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Log berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: '.$e->getMessage(),
            ], 500);
        }
    }

    public function deleteTrafficLogsByDateRange(Request $request)
    {
        try {
            $tgl_awal = $request->input('tgl_awal');
            $tgl_akhir = $request->input('tgl_akhir');
            $type = $request->input('type', 'both'); // 'traffic', 'report', or 'both'

            if (! $tgl_awal || ! $tgl_akhir) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal awal dan akhir harus diisi',
                ], 400);
            }

            $deletedCount = 0;

            // Hapus dari traffic_logs
            if ($type === 'traffic' || $type === 'both') {
                $trafficDeleted = Traffic::whereDate('created_at', '>=', $tgl_awal)
                    ->whereDate('created_at', '<=', $tgl_akhir)
                    ->delete();
                $deletedCount += $trafficDeleted;
            }

            // Hapus dari data table (Report)
            if ($type === 'report' || $type === 'both') {
                $reportDeleted = Report::where('time', '>=', $tgl_awal.' 00:00:00')
                    ->where('time', '<=', $tgl_akhir.' 23:59:59')
                    ->delete();
                $deletedCount += $reportDeleted;
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} log(s)",
                'deleted_count' => $deletedCount,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: '.$e->getMessage(),
            ], 500);
        }
    }
}
