<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Final_Mark extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

        protected $fillable = [
        'mark',
        'group_subject_id',
        'student_id',
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
