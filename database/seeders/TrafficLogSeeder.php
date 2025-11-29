<?php

namespace Database\Seeders;

use App\Models\Traffic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TrafficLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Generating traffic logs data for the last 1 week...');

        // Waktu mulai: 1 minggu yang lalu
        $startDate = Carbon::now()->subWeek();
        // Waktu akhir: sekarang
        $endDate = Carbon::now();

        // Interval: setiap 3 detik (sesuai dengan contoh data)
        // Catatan: Untuk 1 minggu penuh dengan interval 3 detik akan menghasilkan ~201,600 records
        // Jika terlalu banyak, bisa ubah interval menjadi lebih besar (misalnya 30 detik atau 1 menit)
        $interval = 3; // detik

        // Hitung total records yang akan dibuat
        $totalSeconds = $endDate->diffInSeconds($startDate);
        $totalRecords = (int) ($totalSeconds / $interval);

        $this->command->info("Will create approximately {$totalRecords} records...");
        $this->command->warn("This may take several minutes. Please be patient...");

        // Batch insert untuk performa lebih baik
        $batchSize = 500; // Kurangi batch size untuk menghindari memory issue
        $currentTime = $startDate->copy();
        $batch = [];
        $processed = 0;

        $progressBar = $this->command->getOutput()->createProgressBar($totalRecords);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');
        $progressBar->start();

        while ($currentTime->lt($endDate)) {
            // Generate tx_bits dan rx_bits dengan variasi realistis
            // Berdasarkan contoh: tx_bits berkisar 920-12744, rx_bits berkisar 2072-22544
            // Untuk variasi lebih realistis, kita buat range lebih luas dengan beberapa pola
            $baseTx = rand(500, 15000);
            $baseRx = rand(1000, 25000);

            // Tambahkan variasi untuk membuat data lebih realistis (kadang tinggi, kadang rendah)
            $variation = rand(0, 100);
            if ($variation > 80) {
                // 20% chance untuk traffic tinggi
                $txBits = $baseTx + rand(10000, 35000);
                $rxBits = $baseRx + rand(15000, 35000);
            } else {
                // 80% chance untuk traffic normal
                $txBits = $baseTx;
                $rxBits = $baseRx;
            }

            // Hitung Mbps (dibulatkan 2 desimal)
            $txMbps = round($txBits / 1000000, 2);
            $rxMbps = round($rxBits / 1000000, 2);

            $batch[] = [
                'interface_name' => 'ether2-Komputer',
                'tx_bits' => $txBits,
                'rx_bits' => $rxBits,
                'tx_mbps' => $txMbps,
                'rx_mbps' => $rxMbps,
                'mikrotik_id' => 1,
                'created_at' => $currentTime->copy(),
                'updated_at' => $currentTime->copy(),
            ];

            // Jika batch sudah penuh, insert ke database
            if (count($batch) >= $batchSize) {
                try {
                    Traffic::insert($batch);
                    $processed += count($batch);
                    $batch = [];
                    $progressBar->advance($batchSize);
                } catch (\Exception $e) {
                    $this->command->error("Error inserting batch: " . $e->getMessage());
                    break;
                }
            }

            // Tambah interval detik
            $currentTime->addSeconds($interval);
        }

        // Insert sisa batch
        if (!empty($batch)) {
            try {
                Traffic::insert($batch);
                $processed += count($batch);
                $progressBar->advance(count($batch));
            } catch (\Exception $e) {
                $this->command->error("Error inserting final batch: " . $e->getMessage());
            }
        }

        $progressBar->finish();
        $this->command->newLine();
        $this->command->info("✓ Successfully created {$processed} traffic log records!");
        $this->command->info("  Date range: {$startDate->format('Y-m-d H:i:s')} to {$endDate->format('Y-m-d H:i:s')}");
        $this->command->info("  Interval: {$interval} seconds");
    }
}
