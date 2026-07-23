<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';
    protected $primaryKey = 'area_key';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
