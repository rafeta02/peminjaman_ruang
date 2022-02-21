<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLogPinjamRequest;
use App\Http\Requests\UpdateLogPinjamRequest;
use App\Http\Resources\Admin\LogPinjamResource;
use App\Models\LogPinjam;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogPinjamApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('log_pinjam_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LogPinjamResource(LogPinjam::with(['peminjaman'])->get());
    }

    public function store(StoreLogPinjamRequest $request)
    {
        $logPinjam = LogPinjam::create($request->all());

        return (new LogPinjamResource($logPinjam))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LogPinjam $logPinjam)
    {
        abort_if(Gate::denies('log_pinjam_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LogPinjamResource($logPinjam->load(['peminjaman']));
    }

    public function update(UpdateLogPinjamRequest $request, LogPinjam $logPinjam)
    {
        $logPinjam->update($request->all());

        return (new LogPinjamResource($logPinjam))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LogPinjam $logPinjam)
    {
        abort_if(Gate::denies('log_pinjam_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logPinjam->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
