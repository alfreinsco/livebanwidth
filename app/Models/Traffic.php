<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Traffic extends Model
{
    protected $table = 'traffic_logs';

    protected $fillable = [
        'interface_name',
        'tx_bits',
        'rx_bits',
        'tx_mbps',
        'rx_mbps',
        'mikrotik_id',
    ];

    protected $casts = [
        'tx_bits' => 'integer',
        'rx_bits' => 'integer',
        'tx_mbps' => 'decimal:2',
        'rx_mbps' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the MikroTik that owns the traffic log.
     */
    public function mikrotik(): BelongsTo
    {
        return $this->belongsTo(MikroTik::class);
    }
}
