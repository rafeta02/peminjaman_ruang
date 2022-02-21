@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.logPinjam.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.log-pinjams.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="peminjaman_id">{{ trans('cruds.logPinjam.fields.peminjaman') }}</label>
                            <select class="form-control select2" name="peminjaman_id" id="peminjaman_id">
                                @foreach($peminjamen as $id => $entry)
                                    <option value="{{ $id }}" {{ old('peminjaman_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('peminjaman'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('peminjaman') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.logPinjam.fields.peminjaman_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.logPinjam.fields.jenis') }}</label>
                            <select class="form-control" name="jenis" id="jenis">
                                <option value disabled {{ old('jenis', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\LogPinjam::JENIS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('jenis', 'diajukan') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('jenis'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('jenis') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.logPinjam.fields.jenis_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="log">{{ trans('cruds.logPinjam.fields.log') }}</label>
                            <textarea class="form-control" name="log" id="log">{{ old('log') }}</textarea>
                            @if($errors->has('log'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('log') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.logPinjam.fields.log_helper') }}</span>
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