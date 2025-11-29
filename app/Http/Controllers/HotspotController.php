<?php

namespace App\Http\Controllers;

use App\Helpers\MikroTikHelper;
use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class HotspotController extends Controller
{
    public function users()
    {
        $credentials = MikroTikHelper::getCredentials();
        if (!$credentials) {
            return redirect()->route('mikrotik.index')
                ->with('error', 'Silakan pilih atau tambahkan MikroTik terlebih dahulu.');
        }

        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            $hotspotuser = $API->comm('/ip/hotspot/user/print');
            $server      = $API->comm('/ip/hotspot/print');
            $profile     = $API->comm('/ip/hotspot/user/profile/print');

            $data = [
                'totalhotspotuser' => count($hotspotuser),
                'hotspotuser'      => $hotspotuser,
                'server'           => $server,
                'profile'          => $profile,
            ];

            return view('hotspot.user', $data);
        }

        return redirect()->route('failed.view');
    }

    public function add(Request $request)
    {
        $credentials = MikroTikHelper::getCredentials();
        if (!$credentials) {
            return redirect()->route('mikrotik.index')
                ->with('error', 'Silakan pilih atau tambahkan MikroTik terlebih dahulu.');
        }

        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            $timelimit = $request->input('timelimit', '0') === '' ? '0' : $request->input('timelimit');

            $API->comm('/ip/hotspot/user/add', [
                'name'         => $request->input('user'),
                'password'     => $request->input('password'),
                'server'       => $request->input('server'),
                'profile'      => $request->input('profile'),
                'limit-uptime' => $timelimit,
                'comment'      => $request->input('comment'),
            ]);

            if (class_exists(Alert::class)) {
                Alert::success('Success', 'Berhasil menambahkan user Hotspot');
            }

            return redirect()->route('hotspot.users');
        }

        return redirect()->route('failed.view');
    }

    public function edit(string $id)
    {
        $credentials = MikroTikHelper::getCredentials();
        if (!$credentials) {
            return redirect()->route('mikrotik.index')
                ->with('error', 'Silakan pilih atau tambahkan MikroTik terlebih dahulu.');
        }

        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            $getuser = $API->comm('/ip/hotspot/user/print', [
                '?.id' => '*' . $id,
            ]);
            $server  = $API->comm('/ip/hotspot/print');
            $profile = $API->comm('/ip/hotspot/user/profile/print');

            $data = [
                'menu'    => 'Hotspot',
                'user'    => $getuser[0] ?? null,
                'server'  => $server,
                'profile' => $profile,
            ];

            return view('hotspot.edit', $data);
        }

        return redirect()->route('failed.view');
    }

    public function update(Request $request)
    {
        $credentials = MikroTikHelper::getCredentials();
        if (!$credentials) {
            return redirect()->route('mikrotik.index')
                ->with('error', 'Silakan pilih atau tambahkan MikroTik terlebih dahulu.');
        }

        $API = new RouterosAPI();
        $API->debug = false;

        if (!$API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            return redirect()->route('failed.view');
        }

        $timelimit = $request->input('timelimit', '0') === '' ? '0' : $request->input('timelimit');

        $API->comm('/ip/hotspot/user/set', [
            '.id'          => $request->input('id'),
            'name'         => $request->input('user'),
            'password'     => $request->input('password'),
            'server'       => $request->input('server'),
            'profile'      => $request->input('profile'),
            'disabled'     => $request->input('disabled'),
            'limit-uptime' => $timelimit,
            'comment'      => $request->input('comment'),
        ]);

        if (class_exists(Alert::class)) {
            Alert::success('Success', 'Berhasil mengupdate user Hotspot');
        }

        return redirect()->route('hotspot.users');
    }

    public function active()
    {
        $credentials = MikroTikHelper::getCredentials();
        if (!$credentials) {
            return redirect()->route('mikrotik.index')
                ->with('error', 'Silakan pilih atau tambahkan MikroTik terlebih dahulu.');
        }

        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            $hotspotactive = $API->comm('/ip/hotspot/active/print');

            $data = [
                'menu'               => 'Hotspot',
                'totalhotspotactive' => count($hotspotactive),
                'hotspotactive'      => $hotspotactive,
            ];

            return view('hotspot.active', $data);
        }

        return redirect()->route('failed.view');
    }
}


