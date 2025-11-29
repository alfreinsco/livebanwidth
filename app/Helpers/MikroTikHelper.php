<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MikroTikHelper
{
    /**
     * Get MikroTik credentials from authenticated user.
     * Prioritas: active_mikrotik_id dari database > session
     *
     * @return array|null
     */
    public static function getCredentials(): ?array
    {
        if (!Auth::check()) {
            return null;
        }

        $user = Auth::user();

        // Prioritas 1: Ambil dari active_mikrotik_id di database
        if ($user->active_mikrotik_id) {
            // Reload user dengan relasi untuk memastikan data terbaru
            $user->load('activeMikroTik');
            
            if ($user->activeMikroTik) {
                $mikrotik = $user->activeMikroTik;
                
                // Update session untuk konsistensi
                session([
                    'mikrotik_id' => $mikrotik->id,
                    'mikrotik_ip' => $mikrotik->ip_address,
                    'mikrotik_user' => $mikrotik->username,
                    'mikrotik_password' => $mikrotik->password,
                ]);
                
                return [
                    'ip' => $mikrotik->ip_address,
                    'user' => $mikrotik->username,
                    'password' => $mikrotik->password,
                ];
            }
        }

        // Fallback: Cek dari session jika tidak ada di database
        if (session('mikrotik_ip') && session('mikrotik_user') && session('mikrotik_password')) {
            return [
                'ip' => session('mikrotik_ip'),
                'user' => session('mikrotik_user'),
                'password' => session('mikrotik_password'),
            ];
        }

        return null;
    }

    /**
     * Set active MikroTik for user.
     *
     * @param int $mikrotikId
     * @return bool
     */
    public static function setActiveMikroTik(int $mikrotikId): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        $mikrotik = $user->mikrotiks()->where('id', $mikrotikId)->first();

        if (!$mikrotik) {
            return false;
        }

        $user->active_mikrotik_id = $mikrotikId;
        $user->save();

        // Update session
        session([
            'mikrotik_id' => $mikrotik->id,
            'mikrotik_ip' => $mikrotik->ip_address,
            'mikrotik_user' => $mikrotik->username,
            'mikrotik_password' => $mikrotik->password,
        ]);

        return true;
    }
}

