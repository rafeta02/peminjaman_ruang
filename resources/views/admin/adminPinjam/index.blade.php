@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        List Pengajuan Peminjaman Ruang
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Pinjam">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.pinjam.fields.ruang') }}
                    </th>
                    <th>
                        Pemohon
                    </th>
                    <th>
                        Tanggal Pengajuan
                    </th>
                    <th>
                        Waktu Peminjaman
                    </th>
                    <th>
                        {{ trans('cruds.pinjam.fields.penggunaan') }}
                    </th>
                    <th>
                        {{ trans('cruds.pinjam.fields.unit_pengguna') }}
                    </th>
                    <th>
                        {{ trans('cruds.pinjam.fields.surat_pengajuan') }}
                    </th>
                    <th>
                        {{ trans('cruds.pinjam.fields.status') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="rejectionModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reason Rejection</h4>
            </div>
            <div class="modal-body">
                <form id="rejectionForm" class="form-horizontal">
                   <input type="hidden" name="pinjam_id" id="rejection_pinjam_id">
                    <div class="form-group">
                        <label for="driver" class="col-sm-2 control-label">Reason</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="reason_rejection" id="reason_rejection"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger" id="rejectionBtn" value="save">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
@parent
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(function () {
        let dtOverrideGlobals = {
            // buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: "{{ route('admin.process.index') }}",
            columns: [
                {
                    data: 'placeholder',
                    name: 'placeholder'
                },
                {
                    data: 'ruang_name',
                    name: 'ruang_name'
                },
                {
                    data: 'borrowed_by_name',
                    name: 'borrowed_by_name'
                },
                {
                    data: 'tanggal_pengajuan',
                    name: 'tanggal_pengajuan'
                },
                {
                    data: 'waktu_peminjaman',
                    name: 'waktu_peminjaman'
                },
                {
                    data: 'penggunaan',
                    name: 'penggunaan'
                },
                {
                    data: 'unit_pengguna',
                    name: 'unit_pengguna'
                },
                {
                    data: 'surat_pengajuan',
                    name: 'surat_pengajuan',
                    sortable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'actions',
                    name: '{{ trans('global.actions ') }}'
                }
            ],
            orderCellsTop: true,
            order: [
                [1, 'desc']
            ],
            pageLength: 100,
        };
        let table = $('.datatable-Pinjam').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '.button-accept', function () {
            event.preventDefault();
            const id = $(this).data('id');
            swal({
                title: 'Apakah pengajuan akan disetujui ?',
                text: 'Pengajuan peminjaman ruangan akan disetujui',
                icon: 'warning',
                buttons: ["Cancel", "Yes!"],
                showSpinner: true
            }).then(function(value) {
                if (value) {
                    $(".overlay-loading").css("display", "block");
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.process.accept') }}",
                        data: {
                            id: id
                        },
                        success: function (response) {
                            $(".overlay-loading").css("display", "none");
                            if (response.status == 'success') {
                                table.ajax.reload();
                                swal("Success", response.message, "success");
                            } else {
                                swal("Warning!", response.message, 'error');
                            }
                        }
                    });
                }
            });
        });

        $('body').on('click', '.button-reject', function () {
            event.preventDefault();
            const id = $(this).data('id');
            $('#rejection_pinjam_id').val(id);
            $('#rejectionModal').modal('show');
        });

        $('#rejectionBtn').click(function (e) {
            e.preventDefault();
            if (!$.trim($("#reason_rejection").val())) {
                swal("Warning!", 'Reason is empty', 'error');
                return;
            } else {
                swal({
                    title: 'Apakah pengajuan akan ditolak ?',
                    text: 'Pengajuan peminjaman ruang akan ditolak',
                    icon: 'warning',
                    buttons: ["Cancel", "Yes!"],
                    showSpinner: true
                }).then(function(value) {
                    if (value) {
                        $('#rejectionModal').modal('hide');
                        $(".overlay-loading").css("display", "block");
                        $.ajax({
                            data: $('#rejectionForm').serialize(),
                            url: "{{ route('admin.process.reject') }}",
                            type: "POST",
                            dataType: 'json',
                            success: function (response) {
                                $(".overlay-loading").css("display", "none");
                                $('#rejectionForm').trigger("reset");
                                if (response.status == 'success') {
                                    table.ajax.reload();
                                    swal("Success", response.message, 'success');
                                } else {
                                    swal("Warning!", response.message, 'error');
                                }
                            }
                        });
                    }
                });
            }
        });

    });

</script>
@endsection
