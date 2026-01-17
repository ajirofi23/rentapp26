<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'karyawan_id',
        'toy_id',
        'nama_customer',
        'total_harga',
        'jam_mulai',
        'durasi_menit',
        'jam_selesai',
        'status',
    ];

    protected $casts = [
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function toy(): BelongsTo
    {
        return $this->belongsTo(Toy::class);
    }
}
