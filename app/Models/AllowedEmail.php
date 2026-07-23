<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowedEmail extends Model
{
    protected $table = 'allowed_emails';
    protected $guarded = [];
    protected $hidden = ['password'];
    protected $casts = [
        'is_platform_admin' => 'boolean',
    ];
}
