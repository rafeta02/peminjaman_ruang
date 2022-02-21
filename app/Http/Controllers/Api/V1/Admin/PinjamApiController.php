<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePinjamRequest;
use App\Http\Requests\UpdatePinjamRequest;
use App\Http\Resources\Admin\PinjamResource;
use App\Models\Pinjam;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PinjamApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('pinjam_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PinjamResource(Pinjam::with(['ruang', 'borrowed_by', 'processed_by', 'created_by'])->get());
    }

    public function store(StorePinjamRequest $request)
    {
        $pinjam = Pinjam::create($request->all());

        if ($request->input('surat_pengajuan', false)) {
            $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_pengajuan'))))->toMediaCollection('surat_pengajuan');
        }

        return (new PinjamResource($pinjam))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Pinjam $pinjam)
    {
        abort_if(Gate::denies('pinjam_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PinjamResource($pinjam->load(['ruang', 'borrowed_by', 'processed_by', 'created_by']));
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

        return (new PinjamResource($pinjam))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Pinjam $pinjam)
    {
        abort_if(Gate::denies('pinjam_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pinjam->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
