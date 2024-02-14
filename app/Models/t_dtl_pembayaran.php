<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\t_pembayaran;

class t_dtl_pembayaran extends Model
{
    use HasFactory;

    protected $table = 't_dtl_pembayaran';

    protected $primaryKey = 'id_detail_pembayaran';

    protected $fillable = [
        'id_pembayaran',
        'tahun_pembayaran',
        'bulan',
        'jumlah',
    ];

    protected function pembayaran() {
        return $this->belongsTo(t_pembayaran::class, 'id_pembayaran');
    }
}
