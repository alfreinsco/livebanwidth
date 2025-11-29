<?php

namespace Database\Seeders;

use App\Models\MikroTik;
use App\Models\User;
use Illuminate\Database\Seeder;

class MikroTikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MikroTik::updateOrCreate([
            'user_id' => User::first()->id,
            'name' => 'MikroTik Wijaya',
            'ip_address' => '192.168.2.1',
            'username' => 'wijaya',
            'password' => 'mhs201971020',
            'port' => 8728,
            'is_active' => true,
            'description' => 'MikroTik Wijaya',
        ]);
    }
}
