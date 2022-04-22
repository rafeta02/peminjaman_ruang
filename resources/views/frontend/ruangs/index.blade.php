@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            {{-- <div class="card mb-3">
                <div class="card-header">
                    {{ trans('cruds.ruang.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Ruang">
                            <thead>
                                <tr>
                                    <th>
                                        Ruang
                                    </th>
                                    <th>
                                        {{ trans('cruds.ruang.fields.kapasitas') }}
                                    </th>
                                    <th width="10%">
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ruangs as $key => $ruang)
                                    <tr data-entry-id="{{ $ruang->id }}">
                                        <td>
                                            {{ $ruang->nama_lantai ?? '' }}
                                        </td>
                                        <td>
                                            {{ $ruang->kapasitas ?? '' }}
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-block btn-success btn-block" href="{{ route('frontend.pinjams.create', ['ruang' => $ruang->id]) }}">
                                                Ajukan
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}

            <div class="row">
                @foreach($ruangs as $key => $ruang)
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card mb-3">
                        <div id="carouselExample{{ $key }}" class="carousel slide carousel-fade" data-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    @if(count($ruang->images) > 0)
                                        @foreach ($ruang->images as $media)
                                            <img class="d-block w-100" src="{{ $media->getUrl() }}">
                                        @endforeach
                                    @else
                                        <img class="d-block w-100" src="{{ asset('img/empty-room.jpg') }}">
                                    @endif
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExample{{ $key }}" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExample{{ $key }}" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $ruang->nama_lantai ?? '' }}</h5>
                            {{-- <p class="card-text">Description</p> --}}
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>Kapasitas : {{ $ruang->kapasitas ?? '' }} Orang</b></li>
                            <li class="list-group-item"><b>Fasilitas : {{ $ruang->fasilitas ?? '-' }}</b></li>
                            <li class="list-group-item">
                                <a class="btn btn-md btn-block btn-primary" href="{{ route('frontend.pinjams.create', ['ruang' => $ruang->id]) }}">
                                    Ajukan
                                </a>
                            </li>
                          </ul>
                    </div>
                </div>
                @endforeach
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
@can('ruang_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.ruangs.massDestroy') }}",
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
  let table = $('.datatable-Ruang:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection

