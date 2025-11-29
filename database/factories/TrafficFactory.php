<?php

namespace Database\Factories;

use App\Models\Traffic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Traffic>
 */
class TrafficFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Traffic::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate tx_bits dan rx_bits dengan variasi realistis
        // Berdasarkan contoh: tx_bits berkisar 920-12744, rx_bits berkisar 2072-22544
        $txBits = $this->faker->numberBetween(500, 50000);
        $rxBits = $this->faker->numberBetween(1000, 60000);

        // Hitung Mbps (dibulatkan 2 desimal)
        $txMbps = round($txBits / 1000000, 2);
        $rxMbps = round($rxBits / 1000000, 2);

        return [
            'interface_name' => 'ether2-Komputer',
            'tx_bits' => $txBits,
            'rx_bits' => $rxBits,
            'tx_mbps' => $txMbps,
            'rx_mbps' => $rxMbps,
            'mikrotik_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Set custom timestamp for the traffic log.
     */
    public function withTimestamp($timestamp): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);
    }

    /**
     * Set custom interface name.
     */
    public function withInterface(string $interfaceName): static
    {
        return $this->state(fn (array $attributes) => [
            'interface_name' => $interfaceName,
        ]);
    }

    /**
     * Set custom MikroTik ID.
     */
    public function withMikroTik(int $mikrotikId): static
    {
        return $this->state(fn (array $attributes) => [
            'mikrotik_id' => $mikrotikId,
        ]);
    }
}
