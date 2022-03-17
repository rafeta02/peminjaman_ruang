@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.pinjam.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.pinjams.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.id') }}
                        </th>
                        <td>
                            {{ $pinjam->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.ruang') }}
                        </th>
                        <td>
                            {{ $pinjam->ruang->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.time_start') }}
                        </th>
                        <td>
                            {{ $pinjam->time_start }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.time_end') }}
                        </th>
                        <td>
                            {{ $pinjam->time_end }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.time_return') }}
                        </th>
                        <td>
                            {{ $pinjam->time_return }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.no_hp') }}
                        </th>
                        <td>
                            {{ $pinjam->no_hp }}
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
                            {{ App\Models\Pinjam::STATUS_SELECT[$pinjam->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.status_text') }}
                        </th>
                        <td>
                            {{ $pinjam->status_text }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.borrowed_by') }}
                        </th>
                        <td>
                            {{ $pinjam->borrowed_by->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pinjam.fields.processed_by') }}
                        </th>
                        <td>
                            {{ $pinjam->processed_by->name ?? '' }}
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
                <a class="btn btn-default" href="{{ route('admin.pinjams.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection