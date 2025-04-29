<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        "name",
    ];

    protected $hidden = [
        'administrator_id',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'organization_id');
    }
}
