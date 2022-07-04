<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Controllers\Traits\WaBlastTrait;
use App\Http\Requests\MassDestroyPinjamRequest;
use App\Http\Requests\StoreProcessRequest;
use App\Http\Requests\UpdateProcessRequest;
use App\Models\Pinjam;
use App\Models\Ruang;
use App\Models\LogPinjam;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Carbon\Carbon;
use Alert;

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
        abort_if(Gate::denies('process_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruangs = Ruang::get()->pluck('nama_lantai', 'id')->prepend(trans('global.pleaseSelect'), '');
        $users = User::whereHas('roles', function($q){
            $q->where('id', 3);
        })->get()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.adminPinjam.create', compact('ruangs', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProcessRequest $request)
    {
        $times = [
            Carbon::parse($request->input('time_start')),
            Carbon::parse($request->input('time_end')),
        ];

        $countPinjam = Pinjam::where('ruang_id', $request->ruang_id)
                ->where(function($query) use($times) {
                    $query->whereBetween('time_start', $times)
                        ->orWhereBetween('time_end', $times)
                        ->orWhere(function ($query) use ($times) {
                            $query->where('time_start', '<', $times[0])
                                ->where('time_end', '>', $times[1]);
                        });
                })
                ->count();
        if ($countPinjam > 0) {
            Alert::error('Error', 'Ruangan sudah dipinjam untuk waktu peminjaman tersebut!');
            return redirect()->back();
        }

        $pemohon = User::find($request->borrowed_by_id);
        $ruang = Ruang::find($request->ruang_id);
        $request->request->add(['status' => 'disetujui']);
        $request->request->add(['status_text' => 'Diajukan oleh "' . $pemohon->name .'" dan Disetujui oleh "' . auth()->user()->name .'" untuk peminjaman ruang "'.$ruang->nama_lantai .'"']);

        $sukses = DB::transaction(function() use ($request) {
            $pinjam = Pinjam::create($request->all());

            if ($request->input('surat_pengajuan', false)) {
                $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_pengajuan'))))->toMediaCollection('surat_pengajuan');
            }

            if ($media = $request->input('ck-media', false)) {
                Media::whereIn('id', $media)->update(['model_id' => $pinjam->id]);
            }

            $log = LogPinjam::create([
                'peminjaman_id' => $pinjam->id,
                'jenis' => 'disetujui',
                'log' => 'Peminjaman ruang : '. $pinjam->ruang->nama_lantai. ' Diajukan oleh "'. $pinjam->borrowed_by->name.'" Untuk tanggal '. $pinjam->WaktuPeminjaman . ' Untuk penggunaan "' . $pinjam->penggunaan .'" Telah Disetujui oleh "'. auth()->user()->name. '"',
            ]);

            $pesan_user = 'Peminjaman ruang : '. $pinjam->ruang->nama_lantai. ' Diajukan oleh "'. $pinjam->borrowed_by->name.'" Untuk tanggal '. $pinjam->WaktuPeminjaman . ' Untuk penggunaan "' . $pinjam->penggunaan .'" Sudah Diproses dan Disetujui.';

            return (['pesan_admin' => $log['log'], 'pesan_user' => $pesan_user, 'user' => $pinjam->no_hp]);
        });

        return redirect()->route('admin.process.index');
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
        abort_if(Gate::denies('process_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruangs = Ruang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pinjam->load('ruang', 'borrowed_by', 'processed_by', 'created_by');

        return view('admin.adminPinjam.edit', compact('pinjam', 'ruangs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProcessRequest $request, Pinjam $pinjam)
    {
        $pinjam->update($request->all());

        if ($request->input('surat_pengajuan', false)) {
            if (!$pinjam->surat_pengajuan || $request->input('surat_pengajuan') !== $pinjam->surat_pengajuan->file_name) {
                if ($pinjam->surat_pengajuan) {
                    $pinjam->surat_pengajuan->delete();
                }
                $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_pengajuan'))))->toMediaCollection('surat_pengajuan');
            }
        } elseif ($pinjam->surat_pengajuan) {
            $pinjam->surat_pengajuan->delete();
        }

        return redirect()->route('admin.process.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pinjam $pinjam)
    {
        abort_if(Gate::denies('process_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pinjam->delete();

        return back();
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
