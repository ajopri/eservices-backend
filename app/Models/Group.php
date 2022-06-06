<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'contact_code',
        'group_id',
        'default',
        'is_email_verified'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user');
    }
}
