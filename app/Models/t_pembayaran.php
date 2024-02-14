<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\t_pembayaran;
use App\Models\t_dtl_pembayaran;

class t_pembayaran extends Model
{
    use HasFactory;

    protected $table = 't_pembayaran';

    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pembayaran',
        'tgl',
        'nis',
        'total',
    ];

    public function siswa() {
        return $this->belongsTo(t_siswa::class, 'nis', 'nis');
    }

    public function detail() {
        return $this->hasOne(t_dtl_pembayaran::class, 'id_pembayaran', 'id_pembayaran');
    }
}
