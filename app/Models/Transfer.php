<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $table = 'transfers';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
