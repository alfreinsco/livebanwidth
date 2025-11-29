<?php

namespace App\Http\Controllers;

use App\Helpers\MikroTikHelper;
use App\Models\RouterosAPI;

class UseractiveController extends Controller
{
    public function index()
    {
        $credentials = MikroTikHelper::getCredentials();
        if (!$credentials) {
            return redirect()->route('mikrotik.index')
                ->with('error', 'Silakan pilih atau tambahkan MikroTik terlebih dahulu.');
        }

        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            $useractive = $API->comm('/user/active/print');

            $data = [
                'menu'           => 'User',
                'useractive'     => $useractive,
                'totaluseractive'=> count($useractive),
            ];

            return view('useractive.index', $data);
        }

        return redirect()->route('failed.view');
    }

    public function useractive()
    {
        $credentials = MikroTikHelper::getCredentials();
        if (!$credentials) {
            return redirect()->route('mikrotik.index')
                ->with('error', 'Silakan pilih atau tambahkan MikroTik terlebih dahulu.');
        }

        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            $useractive = $API->comm('/user/active/print');

            $data = [
                'useractive' => $useractive,
            ];

            return view('realtime.useractive', $data);
        }

        return redirect()->route('failed.view');
    }
}


