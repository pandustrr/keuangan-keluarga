<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TxTempat extends Model
{
    protected $table = 'tx_tempat';
    protected $primaryKey = 'tx_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
