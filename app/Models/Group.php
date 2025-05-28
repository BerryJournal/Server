<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Group extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'course',
        'max_course',
        'admission_date',
        'classroomTeacher_id',
        'speciality_id',
        'organization_id',
        'isArchive',
    ];

        protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function students()
    {
        return $this->hasMany(User::class)->where('isArchive', false);
    }

    public function subjects()
    {
        return $this->hasMany(Group_Subject::class, 'group_id', 'id')->with('subject');
    }

    public function classroomTeacher()
    {
        return $this->belongsTo(User::class, 'classroomTeacher_id');
    }

    public function speciality()
    {
        return $this->belongsTo(Speciality::class);
    }

}
