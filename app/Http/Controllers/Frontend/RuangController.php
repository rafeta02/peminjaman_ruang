<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRuangRequest;
use App\Http\Requests\StoreRuangRequest;
use App\Http\Requests\UpdateRuangRequest;
use App\Models\Lantai;
use App\Models\Ruang;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RuangController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('ruang_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruangs = Ruang::with(['lantai'])->get();

        return view('frontend.ruangs.index', compact('ruangs'));
    }

    public function create()
    {
        abort_if(Gate::denies('ruang_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lantais = Lantai::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.ruangs.create', compact('lantais'));
    }

    public function store(StoreRuangRequest $request)
    {
        $ruang = Ruang::create($request->all());

        return redirect()->route('frontend.ruangs.index');
    }

    public function edit(Ruang $ruang)
    {
        abort_if(Gate::denies('ruang_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lantais = Lantai::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ruang->load('lantai');

        return view('frontend.ruangs.edit', compact('lantais', 'ruang'));
    }

    public function update(UpdateRuangRequest $request, Ruang $ruang)
    {
        $ruang->update($request->all());

        return redirect()->route('frontend.ruangs.index');
    }

    public function show(Ruang $ruang)
    {
        abort_if(Gate::denies('ruang_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruang->load('lantai');

        return view('frontend.ruangs.show', compact('ruang'));
    }

    public function destroy(Ruang $ruang)
    {
        abort_if(Gate::denies('ruang_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruang->delete();

        return back();
    }

    public function massDestroy(MassDestroyRuangRequest $request)
    {
        Ruang::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
