<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Group_Subject extends Model
{
    use HasFactory;

    protected $table = 'group__subjects';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'group_id',
        'subject_id',
        'group_id',
        'teacher_id',
        'organization_id',
        'course',
        'isArchive'
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->hasOne(User::class,'id','teacher_id');
    }
}
