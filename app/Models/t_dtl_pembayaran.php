<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class t_dtl_pembayaran extends Model
{
    use HasFactory;

    protected $allowed = [
        'id_pembayaran',
        'tahun_pembayaran',
        'bulan',
        'jumlah',
    ];
}
