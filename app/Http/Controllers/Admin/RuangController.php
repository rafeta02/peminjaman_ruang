<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRuangRequest;
use App\Http\Requests\StoreRuangRequest;
use App\Http\Requests\UpdateRuangRequest;
use App\Models\Lantai;
use App\Models\Ruang;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RuangController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('ruang_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Ruang::with(['lantai'])->select(sprintf('%s.*', (new Ruang())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'ruang_show';
                $editGate = 'ruang_edit';
                $deleteGate = 'ruang_delete';
                $crudRoutePart = 'ruangs';

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
            $table->addColumn('lantai_name', function ($row) {
                return $row->lantai ? $row->lantai->name : '';
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('kapasitas', function ($row) {
                return $row->kapasitas ? $row->kapasitas : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'lantai']);

            return $table->make(true);
        }

        return view('admin.ruangs.index');
    }

    public function create()
    {
        abort_if(Gate::denies('ruang_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lantais = Lantai::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ruangs.create', compact('lantais'));
    }

    public function store(StoreRuangRequest $request)
    {
        $ruang = Ruang::create($request->all());

        return redirect()->route('admin.ruangs.index');
    }

    public function edit(Ruang $ruang)
    {
        abort_if(Gate::denies('ruang_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lantais = Lantai::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ruang->load('lantai');

        return view('admin.ruangs.edit', compact('lantais', 'ruang'));
    }

    public function update(UpdateRuangRequest $request, Ruang $ruang)
    {
        $ruang->update($request->all());

        return redirect()->route('admin.ruangs.index');
    }

    public function show(Ruang $ruang)
    {
        abort_if(Gate::denies('ruang_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruang->load('lantai');

        return view('admin.ruangs.show', compact('ruang'));
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
