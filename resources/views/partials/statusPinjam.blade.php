@if($pinjam->status == 'selesai')
    <span class="badge badge-dark">Dikembalikan pada tanggal :<br>{{ $pinjam->date_return_formatted }}</span>
@elseif ($pinjam->status == 'ditolak')
    <span class="badge badge-danger">Ditolak dengan alasan :<br>({{ $pinjam->status_text }})</span>
@elseif ($pinjam->status == 'disetujui')
    <span class="badge badge-warning">{{ App\Models\Pinjam::STATUS_SELECT[$pinjam->status] ?? '' }}</span>
@elseif ($pinjam->status == 'diajukan')
    <span class="badge badge-primary">{{ App\Models\Pinjam::STATUS_SELECT[$pinjam->status] ?? '' }}</span>
@endif
