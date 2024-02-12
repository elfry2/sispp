<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\t_pembayaran;
use App\Models\t_dtl_pembayaran;

class t_pembayaran extends Model
{
    use HasFactory;

    protected $allowed = [
        'nis',
        'total',
    ];

    public function siswa() {
        return $this->belongsTo(t_siswa::class, 'nis', 'nis');
    }

    public function detail() {
        return $this->hasOne(t_dtl_pembayaran::class);
    }
}
