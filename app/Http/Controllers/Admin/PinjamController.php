<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPinjamRequest;
use App\Http\Requests\StorePinjamRequest;
use App\Http\Requests\UpdatePinjamRequest;
use App\Models\Pinjam;
use App\Models\Ruang;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PinjamController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('pinjam_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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

                return view('partials.datatablesActions', compact(
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
                return $row->ruang ? $row->ruang->name : '';
            });

            $table->editColumn('no_hp', function ($row) {
                return $row->no_hp ? $row->no_hp : '';
            });
            $table->editColumn('penggunaan', function ($row) {
                return $row->penggunaan ? $row->penggunaan : '';
            });
            $table->editColumn('unit_pengguna', function ($row) {
                return $row->unit_pengguna ? Pinjam::UNIT_PENGGUNA_SELECT[$row->unit_pengguna] : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? Pinjam::STATUS_SELECT[$row->status] : '';
            });
            $table->editColumn('status_text', function ($row) {
                return $row->status_text ? $row->status_text : '';
            });
            $table->addColumn('borrowed_by_name', function ($row) {
                return $row->borrowed_by ? $row->borrowed_by->name : '';
            });

            $table->addColumn('processed_by_name', function ($row) {
                return $row->processed_by ? $row->processed_by->name : '';
            });

            $table->editColumn('surat_pengajuan', function ($row) {
                return $row->surat_pengajuan ? '<a href="' . $row->surat_pengajuan->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'ruang', 'borrowed_by', 'processed_by', 'surat_pengajuan']);

            return $table->make(true);
        }

        return view('admin.pinjams.index');
    }

    public function create()
    {
        abort_if(Gate::denies('pinjam_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruangs = Ruang::get()->pluck('nama_lantai', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.pinjams.create', compact('ruangs'));
    }

    public function store(StorePinjamRequest $request)
    {
        $pinjam = Pinjam::create($request->all());

        if ($request->input('surat_pengajuan', false)) {
            $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_pengajuan'))))->toMediaCollection('surat_pengajuan');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $pinjam->id]);
        }

        return redirect()->route('admin.pinjams.index');
    }

    public function edit(Pinjam $pinjam)
    {
        abort_if(Gate::denies('pinjam_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruangs = Ruang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pinjam->load('ruang', 'borrowed_by', 'processed_by', 'created_by');

        return view('admin.pinjams.edit', compact('pinjam', 'ruangs'));
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

        return redirect()->route('admin.pinjams.index');
    }

    public function show(Pinjam $pinjam)
    {
        abort_if(Gate::denies('pinjam_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pinjam->load('ruang', 'borrowed_by', 'processed_by', 'created_by');

        return view('admin.pinjams.show', compact('pinjam'));
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
