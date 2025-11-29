<?php

namespace App\Http\Controllers;

use App\Helpers\MikroTikHelper;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        return view('report.traffic-up');
    }

    public function search(Request $request)
    {
        $tgl_awal  = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');

        $data = collect();
        $view_tgl = null;

        if ($tgl_awal && $tgl_akhir) {
            $data = Report::with('mikrotik')
                ->orderBy('time', 'desc')
                ->where('time', '>=', $tgl_awal . ' 00:00:00')
                ->where('time', '<=', $tgl_akhir . ' 23:59:59')
                ->get();

            $view_tgl = "List data Mulai tanggal: $tgl_awal, Sampai tanggal: $tgl_akhir";
        }

        return view('report.search-traffic', compact('data', 'view_tgl'));
    }

    public function load()
    {
        $data = Report::with('mikrotik')
            ->orderBy('time', 'desc')
            ->limit(20)
            ->get();

        return view('realtime.load', compact('data'));
    }

    public function up()
    {
        $mikrotikId = null;
        if (Auth::check() && Auth::user()->active_mikrotik_id) {
            $mikrotikId = Auth::user()->active_mikrotik_id;
        }

        $post = new Report();
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

        $post = new Report();
        $post->text = 'Traffic Internet Stabil, Kurang Dari 50 Mbps';
        $post->mikrotik_id = $mikrotikId;
        $post->save();

        return response()->json($post, 200);
    }
}


