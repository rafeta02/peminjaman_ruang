<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogPinjam extends Model
{
    use SoftDeletes;

    public const JENIS_SELECT = [
        'diajukan'  => 'Diajukan',
        'disetujui' => 'Disetujui',
        'ditolak'   => 'Ditolak',
    ];

    public $table = 'log_pinjams';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'peminjaman_id',
        'jenis',
        'log',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Pinjam::class, 'peminjaman_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
