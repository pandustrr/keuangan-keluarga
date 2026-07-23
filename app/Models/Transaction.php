<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
