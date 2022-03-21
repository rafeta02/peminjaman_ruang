<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Controllers\Traits\WaBlastTrait;
use App\Http\Requests\MassDestroyPinjamRequest;
use App\Http\Requests\StorePinjamRequest;
use App\Http\Requests\UpdatePinjamRequest;
use App\Models\Pinjam;
use App\Models\LogPinjam;
use App\Models\Ruang;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use DB;

class PinjamController extends Controller
{
    use MediaUploadingTrait;
    use WaBlastTrait;

    public function index()
    {
        abort_if(Gate::denies('pinjam_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pinjams = Pinjam::with(['ruang', 'borrowed_by', 'processed_by', 'created_by', 'media'])->get();

        return view('frontend.pinjams.index', compact('pinjams'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('pinjam_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruangs = Ruang::get()->pluck('nama_lantai', 'id')->prepend(trans('global.pleaseSelect'), '');

        if ($request->ruang) {
            session()->flashInput(['ruang_id' => $request->ruang]);
        }

        return view('frontend.pinjams.create', compact('ruangs'));
    }

    public function store(StorePinjamRequest $request)
    {
        $ruang = Ruang::find($request->ruang_id);
        $request->request->add(['status' => 'diajukan']);
        $request->request->add(['status_text' => 'Diajukan oleh "' . auth()->user()->name .'" peminjaman ruang "'.$ruang->nama_lantai .'"']);
        $request->request->add(['borrowed_by_id' => auth()->user()->id]);

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
                'jenis' => 'diajukan',
                'log' => 'Peminjaman ruang : '. $pinjam->ruang->nama_lantai. ' Diajukan oleh "'. $pinjam->borrowed_by->name.'" Untuk tanggal '. $pinjam->WaktuPeminjaman . ' Untuk penggunaan "' . $pinjam->penggunaan .'"',
            ]);

            $pesan_user = 'Peminjaman ruang : '. $pinjam->ruang->nama_lantai. ' Diajukan oleh "'. $pinjam->borrowed_by->name.'" Untuk tanggal '. $pinjam->WaktuPeminjaman . ' Untuk penggunaan "' . $pinjam->penggunaan .'" Sudah Diproses.';

            return (['pesan_admin' => $log['log'], 'pesan_user' => $pesan_user, 'user' => $pinjam->no_hp]);
        });

        // $this->sendNotification('628156700796', $sukses['pesan_lppm']); // for Admin LPPM
        // $this->sendNotification($sukses['user'], $sukses['pesan_user']); // for User

        return redirect()->route('frontend.pinjams.index');
    }

    public function edit(Pinjam $pinjam)
    {
        abort_if(Gate::denies('pinjam_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruangs = Ruang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pinjam->load('ruang', 'borrowed_by', 'processed_by', 'created_by');

        return view('frontend.pinjams.edit', compact('pinjam', 'ruangs'));
    }

    public function update(UpdatePinjamRequest $request, Pinjam $pinjam)
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

        return redirect()->route('frontend.pinjams.index');
    }

    public function show(Pinjam $pinjam)
    {
        abort_if(Gate::denies('pinjam_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pinjam->load('ruang', 'borrowed_by', 'processed_by', 'created_by');

        return view('frontend.pinjams.show', compact('pinjam'));
    }

    public function destroy(Pinjam $pinjam)
    {
        abort_if(Gate::denies('pinjam_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pinjam->delete();

        return back();
    }

    public function massDestroy(MassDestroyPinjamRequest $request)
    {
        Pinjam::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('pinjam_create') && Gate::denies('pinjam_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Pinjam();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
