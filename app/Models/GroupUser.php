<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    use HasFactory;

    protected $table = 'group_user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'contact_code',
        'card_code',
        'group_id',
        'user_id',
        'default',
    ];
}
