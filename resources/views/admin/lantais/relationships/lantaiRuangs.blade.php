<div class="m-3">
    @can('ruang_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.ruangs.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.ruang.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.ruang.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-lantaiRuangs">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.ruang.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.ruang.fields.lantai') }}
                            </th>
                            <th>
                                {{ trans('cruds.ruang.fields.name') }}
                            </th>
                            <th>
                                {{ trans('cruds.ruang.fields.kapasitas') }}
                            </th>
                            <th>
                                {{ trans('cruds.ruang.fields.images') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ruangs as $key => $ruang)
                            <tr data-entry-id="{{ $ruang->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $ruang->id ?? '' }}
                                </td>
                                <td>
                                    {{ $ruang->lantai->name ?? '' }}
                                </td>
                                <td>
                                    {{ $ruang->name ?? '' }}
                                </td>
                                <td>
                                    {{ $ruang->kapasitas ?? '' }}
                                </td>
                                <td>
                                    @foreach($ruang->images as $key => $media)
                                        <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                            <img src="{{ $media->getUrl('thumb') }}">
                                        </a>
                                    @endforeach
                                </td>
                                <td>
                                    @can('ruang_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.ruangs.show', $ruang->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('ruang_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.ruangs.edit', $ruang->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('ruang_delete')
                                        <form action="{{ route('admin.ruangs.destroy', $ruang->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('ruang_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.ruangs.massDestroy') }}",
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
  let table = $('.datatable-lantaiRuangs:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection