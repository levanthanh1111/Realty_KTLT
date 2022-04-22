<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'location','area','price','land_disc',
        'document_id', 'for_sale', 'owner_id', 'created_at', 'updated_at','status'
    ];
    protected $primaryKey = 'land_id';
    protected $table = 'land';


}
