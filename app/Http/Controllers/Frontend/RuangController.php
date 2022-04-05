<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyRuangRequest;
use App\Http\Requests\StoreRuangRequest;
use App\Http\Requests\UpdateRuangRequest;
use App\Models\Lantai;
use App\Models\Ruang;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class RuangController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('front_ruangan'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruangs = Ruang::with(['lantai', 'media'])->get();

        return view('frontend.ruangs.index', compact('ruangs'));
    }

    public function create()
    {
        abort_if(Gate::denies('front_ruangan'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lantais = Lantai::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.ruangs.create', compact('lantais'));
    }

    public function store(StoreRuangRequest $request)
    {
        $ruang = Ruang::create($request->all());

        foreach ($request->input('images', []) as $file) {
            $ruang->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('images');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ruang->id]);
        }

        return redirect()->route('frontend.ruangs.index');
    }

    public function edit(Ruang $ruang)
    {
        abort_if(Gate::denies('front_ruangan'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lantais = Lantai::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ruang->load('lantai');

        return view('frontend.ruangs.edit', compact('lantais', 'ruang'));
    }

    public function update(UpdateRuangRequest $request, Ruang $ruang)
    {
        $ruang->update($request->all());

        if (count($ruang->images) > 0) {
            foreach ($ruang->images as $media) {
                if (!in_array($media->file_name, $request->input('images', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ruang->images->pluck('file_name')->toArray();
        foreach ($request->input('images', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ruang->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('images');
            }
        }

        return redirect()->route('frontend.ruangs.index');
    }

    public function show(Ruang $ruang)
    {
        abort_if(Gate::denies('front_ruangan'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruang->load('lantai');

        return view('frontend.ruangs.show', compact('ruang'));
    }

    public function destroy(Ruang $ruang)
    {
        abort_if(Gate::denies('front_ruangan'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruang->delete();

        return back();
    }

    public function massDestroy(MassDestroyRuangRequest $request)
    {
        Ruang::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('front_ruangan') && Gate::denies('front_ruangan'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Ruang();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
