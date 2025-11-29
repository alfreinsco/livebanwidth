<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $table = 'data';

    protected $fillable = ['text', 'time', 'mikrotik_id'];

    protected $hidden = [];

    public $timestamps = false;

    protected $dates = ['time'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->time)) {
                $model->time = now();
            }
        });
    }

    /**
     * Get the MikroTik that owns the report.
     */
    public function mikrotik(): BelongsTo
    {
        return $this->belongsTo(MikroTik::class);
    }
}
