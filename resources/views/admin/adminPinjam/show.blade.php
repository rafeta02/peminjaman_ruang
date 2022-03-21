@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.pinjam.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.process.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
                @if ($pinjam->status == 'diajukan')
                    {{-- @can('process_accept') --}}
                    <button class="btn btn-md btn-success button-accept" data-id="{{ $pinjam->id }}">Accept</button>
                    <button class="btn btn-md btn-danger button-reject" data-id="{{ $pinjam->id }}">Reject</button>
                    {{-- @endcan --}}
                @endif

            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.ruang') }}
                        </th>
                        <td>
                            {{ $pinjam->ruang->nama_lantai ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.waktu_peminjaman') }}
                        </th>
                        <td>
                            {{ $pinjam->waktu_peminjaman }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.penggunaan') }}
                        </th>
                        <td>
                            {{ $pinjam->penggunaan }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.unit_pengguna') }}
                        </th>
                        <td>
                            {{ App\Models\Pinjam::UNIT_PENGGUNA_SELECT[$pinjam->unit_pengguna] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.status') }}
                        </th>
                        <td>
                            <span class="badge badge-{{ App\Models\Pinjam::STATUS_BACKGROUND[$pinjam->status] }}">{{ App\Models\Pinjam::STATUS_SELECT[$pinjam->status] }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Status Proses
                        </th>
                        <td>
                            @if ($pinjam->status == 'ditolak')
                                Peminjaman "ditolak" dengan alasan "{{ $pinjam->status_text }}"
                            @else
                                {{ $pinjam->status_text }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.borrowed_by') }}
                        </th>
                        <td>
                            <u>{{ $pinjam->borrowed_by->name ?? '' }}</u><br>
                            ({{ $pinjam->no_hp ?? $pinjam->borrowed_by->no_hp }})
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.surat_pengajuan') }}
                        </th>
                        <td>
                            @if($pinjam->surat_pengajuan)
                                <a href="{{ $pinjam->surat_pengajuan->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.process.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
