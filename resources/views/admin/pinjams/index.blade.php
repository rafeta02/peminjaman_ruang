@extends('layouts.admin')
@section('content')
@can('pinjam_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.pinjams.create') }}">
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
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Pinjam">
            <thead>
                <tr>
                    <th width="10">

                    </th>
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
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('pinjam_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.pinjams.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
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

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.pinjams.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'ruang_name', name: 'ruang.name' },
{ data: 'time_start', name: 'time_start' },
{ data: 'time_end', name: 'time_end' },
{ data: 'time_return', name: 'time_return' },
{ data: 'no_hp', name: 'no_hp' },
{ data: 'penggunaan', name: 'penggunaan' },
{ data: 'unit_pengguna', name: 'unit_pengguna' },
{ data: 'status', name: 'status' },
{ data: 'status_text', name: 'status_text' },
{ data: 'borrowed_by_name', name: 'borrowed_by.name' },
{ data: 'processed_by_name', name: 'processed_by.name' },
{ data: 'surat_pengajuan', name: 'surat_pengajuan', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Pinjam').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection