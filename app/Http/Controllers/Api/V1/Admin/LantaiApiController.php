<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLantaiRequest;
use App\Http\Requests\UpdateLantaiRequest;
use App\Http\Resources\Admin\LantaiResource;
use App\Models\Lantai;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LantaiApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('lantai_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LantaiResource(Lantai::all());
    }

    public function store(StoreLantaiRequest $request)
    {
        $lantai = Lantai::create($request->all());

        return (new LantaiResource($lantai))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Lantai $lantai)
    {
        abort_if(Gate::denies('lantai_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LantaiResource($lantai);
    }

    public function update(UpdateLantaiRequest $request, Lantai $lantai)
    {
        $lantai->update($request->all());

        return (new LantaiResource($lantai))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Lantai $lantai)
    {
        abort_if(Gate::denies('lantai_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lantai->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
