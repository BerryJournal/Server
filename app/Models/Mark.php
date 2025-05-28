<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'mark',
        'comment',
        'date_id',
        'student_id',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function date()
    {
        return $this->belongsTo(Date::class);
    }
}
