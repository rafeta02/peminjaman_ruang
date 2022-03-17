@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('pinjam_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.pinjams.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.pinjam.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.pinjam.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Pinjam">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        {{ trans('cruds.pinjam.fields.ruang') }}
                                    </th>
                                    <th class="text-center">
                                        Waktu Peminjaman
                                    </th>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.no_hp') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.penggunaan') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.unit_pengguna') }}
                                    </th>
                                    <th class="text-center">
                                        {{ trans('cruds.pinjam.fields.status') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pinjams as $key => $pinjam)
                                    <tr data-entry-id="{{ $pinjam->id }}">
                                        <td>
                                            {{ $pinjam->ruang->nama_lantai ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pinjam->waktu_peminjaman ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pinjam->no_hp ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pinjam->penggunaan ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\Pinjam::UNIT_PENGGUNA_SELECT[$pinjam->unit_pengguna] ?? '' }}
                                        </td>
                                        <td class="text-center">
                                            @include('partials.statusPinjam')
                                        </td>
                                        <td>
                                            @can('pinjam_show')
                                                <a class="btn btn-sm btn-primary btn-block mb-1" href="{{ route('frontend.pinjams.show', $pinjam->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('pinjam_edit')
                                                <a class="btn btn-sm btn-info btn-block mb-1" href="{{ route('frontend.pinjams.edit', $pinjam->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('pinjam_delete')
                                                <form action="{{ route('frontend.pinjams.destroy', $pinjam->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-sm btn-danger btn-block mb-1" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [
                [1, 'desc']
            ],
            pageLength: 100,
        });
        let table = $('.datatable-Pinjam:not(.ajaxTable)').DataTable();
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });

    })

</script>
@endsection
