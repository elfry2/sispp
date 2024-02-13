<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class t_dtl_pembayaran extends Model
{
    use HasFactory;

    protected $table = 't_dtl_pembayaran';

    protected $fillable = [
        'id_pembayaran',
        'tahun_pembayaran',
        'bulan',
        'jumlah',
    ];
}
