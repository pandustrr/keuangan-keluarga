<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferTempat extends Model
{
    protected $table = 'transfer_tempat';
    protected $primaryKey = 'transfer_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
