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
                                    <th>
                                        {{ trans('cruds.pinjam.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.ruang') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.time_start') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.time_end') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.time_return') }}
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
                                    <th>
                                        {{ trans('cruds.pinjam.fields.status') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.status_text') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.borrowed_by') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.processed_by') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.surat_pengajuan') }}
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
                                            {{ $pinjam->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pinjam->ruang->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pinjam->time_start ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pinjam->time_end ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pinjam->time_return ?? '' }}
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
                                        <td>
                                            {{ App\Models\Pinjam::STATUS_SELECT[$pinjam->status] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pinjam->status_text ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pinjam->borrowed_by->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pinjam->processed_by->name ?? '' }}
                                        </td>
                                        <td>
                                            @if($pinjam->surat_pengajuan)
                                                <a href="{{ $pinjam->surat_pengajuan->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @can('pinjam_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.pinjams.show', $pinjam->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('pinjam_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.pinjams.edit', $pinjam->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('pinjam_delete')
                                                <form action="{{ route('frontend.pinjams.destroy', $pinjam->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
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
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('pinjam_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.pinjams.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Pinjam:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection