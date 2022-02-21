<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLantaiRequest;
use App\Http\Requests\StoreLantaiRequest;
use App\Http\Requests\UpdateLantaiRequest;
use App\Models\Lantai;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LantaiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('lantai_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lantais = Lantai::all();

        return view('frontend.lantais.index', compact('lantais'));
    }

    public function create()
    {
        abort_if(Gate::denies('lantai_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.lantais.create');
    }

    public function store(StoreLantaiRequest $request)
    {
        $lantai = Lantai::create($request->all());

        return redirect()->route('frontend.lantais.index');
    }

    public function edit(Lantai $lantai)
    {
        abort_if(Gate::denies('lantai_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.lantais.edit', compact('lantai'));
    }

    public function update(UpdateLantaiRequest $request, Lantai $lantai)
    {
        $lantai->update($request->all());

        return redirect()->route('frontend.lantais.index');
    }

    public function show(Lantai $lantai)
    {
        abort_if(Gate::denies('lantai_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lantai->load('lantaiRuangs');

        return view('frontend.lantais.show', compact('lantai'));
    }

    public function destroy(Lantai $lantai)
    {
        abort_if(Gate::denies('lantai_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lantai->delete();

        return back();
    }

    public function massDestroy(MassDestroyLantaiRequest $request)
    {
        Lantai::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
