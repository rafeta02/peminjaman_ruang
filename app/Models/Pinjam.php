<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Pinjam extends Model implements HasMedia
{
    use SoftDeletes;
    use MultiTenantModelTrait;
    use HasMediaTrait;
    use Auditable;

    public const UNIT_PENGGUNA_SELECT = [
        'lppm'  => 'LPPM',
        'pusdi' => 'PUSDI',
    ];

    public const STATUS_SELECT = [
        'diajukan'  => 'Diajukan',
        'disetujui' => 'Disetujui',
        'ditolak'   => 'Ditolak',
        'selesai'   => 'Selesai',
    ];

    public $table = 'pinjams';

    protected $appends = [
        'surat_pengajuan',
    ];

    protected $dates = [
        'time_start',
        'time_end',
        'time_return',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'ruang_id',
        'time_start',
        'time_end',
        'time_return',
        'penggunaan',
        'unit_pengguna',
        'status',
        'status_text',
        'borrowed_by_id',
        'processed_by_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by_id',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    public function getTimeStartAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.clock_format')) : null;
    }

    public function setTimeStartAttribute($value)
    {
        $this->attributes['time_start'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.clock_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getTimeEndAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.clock_format')) : null;
    }

    public function setTimeEndAttribute($value)
    {
        $this->attributes['time_end'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.clock_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getTimeReturnAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.clock_format')) : null;
    }

    public function setTimeReturnAttribute($value)
    {
        $this->attributes['time_return'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.clock_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function borrowed_by()
    {
        return $this->belongsTo(User::class, 'borrowed_by_id');
    }

    public function processed_by()
    {
        return $this->belongsTo(User::class, 'processed_by_id');
    }

    public function getSuratPengajuanAttribute()
    {
        return $this->getMedia('surat_pengajuan')->last();
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function gettanggalPengajuanAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d F Y');
    }

    public function getDateReturnFormattedAttribute()
    {
        if ($this->attributes['date_return'] == null) {
            return null;
        }

        return Carbon::parse($this->attributes['date_return'])->format('d F Y');
    }

    public function getWaktuPeminjamanAttribute()
    {
        $time_start = Carbon::parse($this->attributes['time_start'])->format('d M Y H:i');
        $time_end = Carbon::parse($this->attributes['time_end'])->format('d M Y H:i');
        return $time_start. ' - '. $time_end;
    }
}
