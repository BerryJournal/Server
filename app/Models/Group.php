<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'course',
        'max_course',
        'admissionDate',
        'classroomteacher_id',
        'speciality_id',
        'organization_id',
        'isArchive',
    ];

    public function students()
    {
        return $this->hasMany(User::class);
    }

    public function classroomTeacher()
    {
        return $this->belongsTo(User::class, 'classroomTeacher_id');
    }

}
