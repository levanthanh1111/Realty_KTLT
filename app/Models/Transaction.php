<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'owner_id','buyer_id','land_id','commision_money',
         'create_at', 'update_at','status'
    ];
    protected $table = 'transaction';
}
