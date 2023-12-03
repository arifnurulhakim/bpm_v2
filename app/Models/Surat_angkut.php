<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat_angkut extends Model
{
    protected $table = 'surat_angkuts';
    protected $primaryKey = 'id_sa';
    protected $guarded = [];
}
