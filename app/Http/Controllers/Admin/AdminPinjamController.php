<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Controllers\Traits\WaBlastTrait;
use App\Http\Requests\MassDestroyPinjamRequest;
use App\Http\Requests\StorePinjamRequest;
use App\Http\Requests\UpdatePinjamRequest;
use App\Models\Pinjam;
use App\Models\Ruang;
use App\Models\LogPinjam;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Carbon\Carbon;

class AdminPinjamController extends Controller
{
    use MediaUploadingTrait;
    use WaBlastTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('process_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Pinjam::with(['ruang', 'borrowed_by', 'processed_by', 'created_by'])->select(sprintf('%s.*', (new Pinjam())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'pinjam_show';
                $editGate = 'pinjam_edit';
                $deleteGate = 'pinjam_delete';
                $crudRoutePart = 'pinjams';

                return view('partials.admintablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });

            $table->addColumn('ruang_name', function ($row) {
                return $row->ruang ? '<b>'.$row->ruang->nama_lantai.'</b>' : '';
            });

            $table->addColumn('waktu_peminjaman', function ($row) {
                return $row->waktu_peminjaman;
            });

            $table->editColumn('penggunaan', function ($row) {
                return $row->penggunaan ? $row->penggunaan : '';
            });
            $table->editColumn('unit_pengguna', function ($row) {
                return $row->unit_pengguna ? Pinjam::UNIT_PENGGUNA_SELECT[$row->unit_pengguna] : '';
            });
            $table->editColumn('status', function ($row) {
                if ($row->status == 'ditolak') {
                    return '<span class="badge badge-danger">Ditolak<br>("'. $row->status_text. '")</span>';
                } else {
                    return '<span class="badge badge-'.Pinjam::STATUS_BACKGROUND[$row->status].'">'.Pinjam::STATUS_SELECT[$row->status].'</span><br>';
                }
            });
            $table->editColumn('status_text', function ($row) {
                return $row->status_text ? $row->status_text : '';
            });
            $table->addColumn('borrowed_by_name', function ($row) {
                return $row->borrowed_by ? ('<u>'.$row->borrowed_by->name.'</u><br>No HP :<br>('.($row->no_hp ?? $row->borrowed_by->no_hp).')') : '';
            });

            $table->addColumn('processed_by_name', function ($row) {
                return $row->processed_by ? $row->processed_by->name : '';
            });

            $table->editColumn('surat_pengajuan', function ($row) {
                return $row->surat_pengajuan ? '<a href="' . $row->surat_pengajuan->getFullUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });

            $table->addColumn('tanggal_pengajuan', function ($row) {
                return $row->tanggal_pengajuan;
            });

            $table->rawColumns(['actions', 'placeholder', 'ruang_name', 'borrowed_by_name', 'processed_by', 'surat_pengajuan', 'status']);

            return $table->make(true);
        }

        return view('admin.adminPinjam.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePinjamRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('process_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pinjam = Pinjam::with('ruang', 'borrowed_by', 'processed_by', 'created_by')->find($id);

        return view('admin.adminPinjam.show', compact('pinjam'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Pinjam $pinjam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePinjamRequest $request, Pinjam $pinjam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pinjam $pinjam)
    {
        //
    }

    public function acceptPengajuan(Request $request)
    {
        try {
            $sukses = DB::transaction(function() use ($request) {
                $data = Pinjam::find($request->id);
                $data->status = 'disetujui';
                $data->status_text = 'Peminjaman ruang "'. $data->ruang->nama_lantai .'" Disetujui oleh "'. auth()->user()->name .'"';

                $log = LogPinjam::create([
                    'peminjaman_id' => $data->id,
                    'jenis' => 'disetujui',
                    'log' => 'Peminjaman ruang '. $data->ruang->nama_lantai. ' Untuk tanggal '. $data->WaktuPeminjaman . '  telah Disetujui oleh "'. auth()->user()->name,
                ]);

                $data->save();

                return (['user' => $data->no_hp, 'pesan' => $log['log']]);
            });

            $this->sendNotification($sukses['user'], $sukses['pesan']); // for User

            return response()->json(['status' => 'success', 'message' => 'Pengajuan berhasil disetujui']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function reject(Request $request)
    {
        try {
            $sukses = DB::transaction(function() use ($request) {
                $data = Pinjam::find($request->pinjam_id);
                $data->status = 'ditolak';
                $data->status_text = $request->reason_rejection;

                $log = LogPinjam::create([
                    'peminjaman_id' => $data->id,
                    'jenis' => 'disetujui',
                    'log' => 'Peminjaman ruang '. $data->ruang->nama_lantai. ' Untuk tanggal '. $data->WaktuPeminjaman . ' telah Ditolak oleh "'. auth()->user()->name .'" dengan alasan "'. $data->status_text .'", Peminjaman ditolak.',
                ]);

                $data->save();

                return (['pesan' => $log['log'], 'user' => $data->no_hp]);
            });

            $this->sendNotification($sukses['user'], $sukses['pesan']); // for User

            return response()->json(['status' => 'success', 'message' => 'Peminjaman telah ditolak']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
