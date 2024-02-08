<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\t_kelas;

class t_siswa extends Model
{
    use HasFactory;

    protected $table = 't_siswa';

    protected $fillable = [
        'nis',
        'nama_siswa',
        'alamat',
        'tgl_lahir',
        'tempat_lahir',
        'jk',
        'nama_orang_tua',
        'no_hp',
        'kd_kls',
        'spp_perbulan',
    ];

    public function kelas() {
        return $this->belongsTo(t_kelas::class, 'kd_kls', 'kd_kls');
    }
}
