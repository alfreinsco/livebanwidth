<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MikroTik extends Model
{
    protected $table = 'mikrotiks';

    protected $fillable = [
        'user_id',
        'name',
        'ip_address',
        'username',
        'password',
        'port',
        'is_active',
        'description',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'port' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the MikroTik.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Encrypt password before saving.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = encrypt($value);
    }

    /**
     * Decrypt password when retrieving.
     */
    public function getPasswordAttribute($value)
    {
        try {
            return decrypt($value);
        } catch (\Exception $e) {
            return $value; // Return as is if decryption fails
        }
    }
}
