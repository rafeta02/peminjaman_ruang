<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRuangRequest;
use App\Http\Requests\UpdateRuangRequest;
use App\Http\Resources\Admin\RuangResource;
use App\Models\Ruang;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RuangApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('ruang_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RuangResource(Ruang::with(['lantai'])->get());
    }

    public function store(StoreRuangRequest $request)
    {
        $ruang = Ruang::create($request->all());

        return (new RuangResource($ruang))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Ruang $ruang)
    {
        abort_if(Gate::denies('ruang_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RuangResource($ruang->load(['lantai']));
    }

    public function update(UpdateRuangRequest $request, Ruang $ruang)
    {
        $ruang->update($request->all());

        return (new RuangResource($ruang))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Ruang $ruang)
    {
        abort_if(Gate::denies('ruang_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruang->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
