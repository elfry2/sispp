<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\t_siswa;

class t_kelas extends Model
{
    use HasFactory;

    protected $table = 't_kelas';

    protected $primaryKey = 'kd_kls';

    protected $fillable = [
        'nm_kelas',
        'jumlah_siswa',
    ];

    public function siswa() {
        return $this->hasMany(t_siswa::class, 'kd_kls');
    }
}
