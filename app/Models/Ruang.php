<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ruang extends Model
{
    use SoftDeletes;
    use Auditable;

    public $table = 'ruangs';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'lantai_id',
        'name',
        'kapasitas',
        'slug',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    /**
     * @var array
     */
    protected $appends = ['nama_lantai'];

    public function lantai()
    {
        return $this->belongsTo(Lantai::class, 'lantai_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getNamaLantaiAttribute()
    {
        return Str::title($this->attributes['name']).' - '.Str::title($this->lantai->name);
    }
}
