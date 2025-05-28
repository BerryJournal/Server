<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Date extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'group_subject_id',
        'date',
        'theme',
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

        public function skips()
    {
        return $this->hasMany(Skip::class);
    }

}
