<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'data';

    protected $fillable = ['text', 'time'];

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
}
