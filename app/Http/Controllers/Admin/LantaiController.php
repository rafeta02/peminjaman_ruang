<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLantaiRequest;
use App\Http\Requests\StoreLantaiRequest;
use App\Http\Requests\UpdateLantaiRequest;
use App\Models\Lantai;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LantaiController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('lantai_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Lantai::query()->select(sprintf('%s.*', (new Lantai())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'lantai_show';
                $editGate = 'lantai_edit';
                $deleteGate = 'lantai_delete';
                $crudRoutePart = 'lantais';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.lantais.index');
    }

    public function create()
    {
        abort_if(Gate::denies('lantai_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.lantais.create');
    }

    public function store(StoreLantaiRequest $request)
    {
        $lantai = Lantai::create($request->all());

        return redirect()->route('admin.lantais.index');
    }

    public function edit(Lantai $lantai)
    {
        abort_if(Gate::denies('lantai_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.lantais.edit', compact('lantai'));
    }

    public function update(UpdateLantaiRequest $request, Lantai $lantai)
    {
        $lantai->update($request->all());

        return redirect()->route('admin.lantais.index');
    }

    public function show(Lantai $lantai)
    {
        abort_if(Gate::denies('lantai_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lantai->load('lantaiRuangs');

        return view('admin.lantais.show', compact('lantai'));
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
