<?php

namespace App\Http\Controllers;

use App\Helpers\MikroTikHelper;
use App\Models\MikroTik;
use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MikroTikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mikrotiks = Auth::user()->mikrotiks()->orderBy('created_at', 'desc')->get();
        $activeMikroTik = Auth::user()->activeMikroTik;

        return view('mikrotik.index', compact('mikrotiks', 'activeMikroTik'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mikrotik.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'ip_address' => 'required|ip',
            'username' => 'required|string|max:100',
            'password' => 'required|string',
            'port' => 'nullable|integer|min:1|max:65535',
            'description' => 'nullable|string',
        ]);

        // Test connection
        $API = new RouterosAPI();
        $API->debug = false;
        $API->timeout = 5;
        $API->attempts = 2;
        $port = $request->port ?? 8728;
        $API->port = $port;

        if (!$API->connect($request->ip_address, $request->username, $request->password)) {
            return back()->withInput()->with('error', 'Gagal terhubung ke router MikroTik. Pastikan IP, username, dan password benar.');
        }

        $API->disconnect();

        $mikrotik = Auth::user()->mikrotiks()->create([
            'name' => $request->name,
            'ip_address' => $request->ip_address,
            'username' => $request->username,
            'password' => $request->password,
            'port' => $port,
            'description' => $request->description,
            'is_active' => false,
        ]);

        // Jika ini adalah MikroTik pertama, set sebagai aktif
        if (Auth::user()->mikrotiks()->count() == 1) {
            MikroTikHelper::setActiveMikroTik($mikrotik->id);
        }

        return redirect()->route('mikrotik.index')
            ->with('success', 'MikroTik berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mikrotik = Auth::user()->mikrotiks()->findOrFail($id);
        
        // Test connection
        $API = new RouterosAPI();
        $API->debug = false;
        $API->timeout = 5;
        $API->attempts = 2;
        $API->port = $mikrotik->port;
        
        $isConnected = false;
        $routerInfo = null;
        
        if ($API->connect($mikrotik->ip_address, $mikrotik->username, $mikrotik->password)) {
            $isConnected = true;
            $identity = $API->comm('/system/identity/print');
            $resource = $API->comm('/system/resource/print');
            $routerboard = $API->comm('/system/routerboard/print');
            
            $routerInfo = [
                'identity' => $identity[0]['name'] ?? 'N/A',
                'version' => $resource[0]['version'] ?? 'N/A',
                'model' => $routerboard[0]['model'] ?? 'N/A',
                'board_name' => $resource[0]['board-name'] ?? 'N/A',
                'uptime' => $resource[0]['uptime'] ?? 'N/A',
            ];
            
            $API->disconnect();
        }

        return view('mikrotik.show', compact('mikrotik', 'isConnected', 'routerInfo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mikrotik = Auth::user()->mikrotiks()->findOrFail($id);
        return view('mikrotik.edit', compact('mikrotik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $mikrotik = Auth::user()->mikrotiks()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'ip_address' => 'required|ip',
            'username' => 'required|string|max:100',
            'password' => 'nullable|string',
            'port' => 'nullable|integer|min:1|max:65535',
            'description' => 'nullable|string',
        ]);

        // Test connection jika password diubah
        if ($request->filled('password')) {
            $API = new RouterosAPI();
            $API->debug = false;
            $API->timeout = 5;
            $API->attempts = 2;
            $port = $request->port ?? $mikrotik->port;
            $API->port = $port;

            if (!$API->connect($request->ip_address, $request->username, $request->password)) {
                return back()->withInput()->with('error', 'Gagal terhubung ke router MikroTik. Pastikan IP, username, dan password benar.');
            }
            $API->disconnect();
        }

        $mikrotik->update([
            'name' => $request->name,
            'ip_address' => $request->ip_address,
            'username' => $request->username,
            'port' => $request->port ?? $mikrotik->port,
            'description' => $request->description,
        ]);

        if ($request->filled('password')) {
            $mikrotik->password = $request->password;
            $mikrotik->save();
        }

        // Update session jika ini adalah MikroTik aktif
        if ($mikrotik->id == Auth::user()->active_mikrotik_id) {
            MikroTikHelper::setActiveMikroTik($mikrotik->id);
        }

        return redirect()->route('mikrotik.index')
            ->with('success', 'MikroTik berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mikrotik = Auth::user()->mikrotiks()->findOrFail($id);

        // Jika ini adalah MikroTik aktif, hapus dari user
        if ($mikrotik->id == Auth::user()->active_mikrotik_id) {
            Auth::user()->update(['active_mikrotik_id' => null]);
            session()->forget(['mikrotik_id', 'mikrotik_ip', 'mikrotik_user', 'mikrotik_password']);
        }

        $mikrotik->delete();

        return redirect()->route('mikrotik.index')
            ->with('success', 'MikroTik berhasil dihapus.');
    }

    /**
     * Set active MikroTik.
     */
    public function setActive(Request $request, string $id)
    {
        $mikrotik = Auth::user()->mikrotiks()->findOrFail($id);

        if (MikroTikHelper::setActiveMikroTik($mikrotik->id)) {
            return redirect()->route('mikrotik.index')
                ->with('success', 'MikroTik aktif berhasil diubah.');
        }

        return redirect()->route('mikrotik.index')
            ->with('error', 'Gagal mengubah MikroTik aktif.');
    }
}
