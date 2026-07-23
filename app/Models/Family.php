<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $table = 'families';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
