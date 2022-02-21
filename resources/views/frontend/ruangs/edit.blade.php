@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.ruang.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.ruangs.update", [$ruang->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="lantai_id">{{ trans('cruds.ruang.fields.lantai') }}</label>
                            <select class="form-control select2" name="lantai_id" id="lantai_id" required>
                                @foreach($lantais as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('lantai_id') ? old('lantai_id') : $ruang->lantai->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('lantai'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('lantai') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.ruang.fields.lantai_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.ruang.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $ruang->name) }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.ruang.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="kapasitas">{{ trans('cruds.ruang.fields.kapasitas') }}</label>
                            <input class="form-control" type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas', $ruang->kapasitas) }}" step="1" required>
                            @if($errors->has('kapasitas'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('kapasitas') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.ruang.fields.kapasitas_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection