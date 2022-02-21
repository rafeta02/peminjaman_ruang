@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('log_pinjam_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.log-pinjams.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.logPinjam.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.logPinjam.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-LogPinjam">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.logPinjam.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.logPinjam.fields.peminjaman') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.time_start') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.time_end') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.logPinjam.fields.jenis') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.logPinjam.fields.log') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logPinjams as $key => $logPinjam)
                                    <tr data-entry-id="{{ $logPinjam->id }}">
                                        <td>
                                            {{ $logPinjam->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $logPinjam->peminjaman->penggunaan ?? '' }}
                                        </td>
                                        <td>
                                            {{ $logPinjam->peminjaman->time_start ?? '' }}
                                        </td>
                                        <td>
                                            {{ $logPinjam->peminjaman->time_end ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\LogPinjam::JENIS_SELECT[$logPinjam->jenis] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $logPinjam->log ?? '' }}
                                        </td>
                                        <td>
                                            @can('log_pinjam_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.log-pinjams.show', $logPinjam->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('log_pinjam_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.log-pinjams.edit', $logPinjam->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('log_pinjam_delete')
                                                <form action="{{ route('frontend.log-pinjams.destroy', $logPinjam->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('log_pinjam_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.log-pinjams.massDestroy') }}",
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
  let table = $('.datatable-LogPinjam:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection