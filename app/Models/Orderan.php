<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderan extends Model
{
    use HasFactory;

    protected $table = 'orderans';
    protected $primaryKey = 'id_orderan';
    protected $guarded = [];

    // Enable timestamps
    public $timestamps = true;

    public function get_orderan($get_orderan){
        // Your method logic here
    }
}
