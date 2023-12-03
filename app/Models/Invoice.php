<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_kwitansi',
        'nomor_invoice',
        'sa_id',
        'nama',
        'tagihan_by',
        'status',
        'tanggal_lunas',
    ];

}
