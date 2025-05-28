<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Subject extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'organization_id',
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class)
            ->using(Group_Subject::class)
            ->withPivot('id', 'teacher_id', 'course');
    }
}
