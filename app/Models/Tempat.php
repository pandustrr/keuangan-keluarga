<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tempat extends Model
{
    protected $table = 'tempat';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
