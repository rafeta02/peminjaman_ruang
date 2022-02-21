@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.ruang.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ruangs.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="lantai_id">{{ trans('cruds.ruang.fields.lantai') }}</label>
                <select class="form-control select2 {{ $errors->has('lantai') ? 'is-invalid' : '' }}" name="lantai_id" id="lantai_id" required>
                    @foreach($lantais as $id => $entry)
                        <option value="{{ $id }}" {{ old('lantai_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('lantai'))
                    <span class="text-danger">{{ $errors->first('lantai') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.ruang.fields.lantai_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.ruang.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.ruang.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="kapasitas">{{ trans('cruds.ruang.fields.kapasitas') }}</label>
                <input class="form-control {{ $errors->has('kapasitas') ? 'is-invalid' : '' }}" type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas', '0') }}" step="1" required>
                @if($errors->has('kapasitas'))
                    <span class="text-danger">{{ $errors->first('kapasitas') }}</span>
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



@endsection