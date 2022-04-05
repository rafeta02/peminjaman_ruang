<a href="{{ route('admin.process.show', $row->id) }}" class="btn btn-sm btn-block mb-1 btn-primary" >View</a>

@if ($row->status == 'diajukan')
    @can('process_accept')
    <button class="btn btn-sm btn-block mb-1 btn-success button-accept" data-id="{{ $row->id }}">Accept</button>
    <button class="btn btn-sm btn-block mb-1 btn-danger button-reject" data-id="{{ $row->id }}">Reject</button>
    @endcan
@endif
