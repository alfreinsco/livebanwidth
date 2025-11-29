<?php

namespace App\Http\Controllers;

use App\Helpers\MikroTikHelper;
use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PPPoEController extends Controller
{
    public function secret()
    {
        $credentials = MikroTikHelper::getCredentials();
        if (!$credentials) {
            return redirect()->route('mikrotik.index')
                ->with('error', 'Silakan pilih atau tambahkan MikroTik terlebih dahulu.');
        }

        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            $secret  = $API->comm('/ppp/secret/print');
            $profile = $API->comm('/ppp/profile/print');

            $data = [
                'menu'       => 'PPPoE',
                'totalsecret'=> count($secret),
                'secret'     => $secret,
                'profile'    => $profile,
            ];

            return view('pppoe.secret', $data);
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
            $API->comm('/ppp/secret/add', [
                'name'           => $request->input('user'),
                'password'       => $request->input('password'),
                'service'        => $request->input('service', 'any') ?: 'any',
                'profile'        => $request->input('profile', 'default') ?: 'default',
                'local-address'  => $request->input('localaddress', '0.0.0.0') ?: '0.0.0.0',
                'remote-address' => $request->input('remoteaddress', '0.0.0.0') ?: '0.0.0.0',
                'comment'        => $request->input('comment', ''),
            ]);

            if (class_exists(Alert::class)) {
                Alert::success('Success', 'Berhasil menambahkan secret PPPoE');
            }

            return redirect()->route('pppoe.secret');
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
            $getuser = $API->comm('/ppp/secret/print', [
                '?.id' => '*' . $id,
            ]);

            $secret  = $API->comm('/ppp/secret/print');
            $profile = $API->comm('/ppp/profile/print');

            $data = [
                'user'    => $getuser[0] ?? null,
                'secret'  => $secret,
                'profile' => $profile,
            ];

            return view('pppoe.edit', $data);
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

        $API->comm('/ppp/secret/set', [
            '.id'            => $request->input('id'),
            'name'           => $request->input('user'),
            'password'       => $request->input('password'),
            'service'        => $request->input('service'),
            'profile'        => $request->input('profile'),
            'disabled'       => $request->input('disabled'),
            'local-address'  => $request->input('localaddress'),
            'remote-address' => $request->input('remoteaddress'),
            'comment'        => $request->input('comment'),
        ]);

        if (class_exists(Alert::class)) {
            Alert::success('Success', 'Berhasil mengupdate secret PPPoE');
        }

        return redirect()->route('pppoe.secret');
    }

    public function delete(string $id)
    {
        $credentials = MikroTikHelper::getCredentials();
        if (!$credentials) {
            return redirect()->route('mikrotik.index')
                ->with('error', 'Silakan pilih atau tambahkan MikroTik terlebih dahulu.');
        }

        $API = new RouterosAPI();
        $API->debug = false;

        if ($API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            $API->comm('/ppp/secret/remove', [
                '.id' => '*' . $id,
            ]);

            if (class_exists(Alert::class)) {
                Alert::success('Success', 'Berhasil menghapus secret PPPoE');
            }

            return redirect()->route('pppoe.secret');
        }

        return redirect()->route('failed.view');
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
            $secretactive = $API->comm('/ppp/active/print');

            $data = [
                'totalsecretactive' => count($secretactive),
                'active'            => $secretactive,
            ];

            return view('pppoe.active', $data);
        }

        return redirect()->route('failed.view');
    }
}


