@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.logPinjam.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.log-pinjams.update", [$logPinjam->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="peminjaman_id">{{ trans('cruds.logPinjam.fields.peminjaman') }}</label>
                <select class="form-control select2 {{ $errors->has('peminjaman') ? 'is-invalid' : '' }}" name="peminjaman_id" id="peminjaman_id">
                    @foreach($peminjamen as $id => $entry)
                        <option value="{{ $id }}" {{ (old('peminjaman_id') ? old('peminjaman_id') : $logPinjam->peminjaman->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('peminjaman'))
                    <span class="text-danger">{{ $errors->first('peminjaman') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.logPinjam.fields.peminjaman_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.logPinjam.fields.jenis') }}</label>
                <select class="form-control {{ $errors->has('jenis') ? 'is-invalid' : '' }}" name="jenis" id="jenis">
                    <option value disabled {{ old('jenis', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\LogPinjam::JENIS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('jenis', $logPinjam->jenis) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('jenis'))
                    <span class="text-danger">{{ $errors->first('jenis') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.logPinjam.fields.jenis_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="log">{{ trans('cruds.logPinjam.fields.log') }}</label>
                <textarea class="form-control {{ $errors->has('log') ? 'is-invalid' : '' }}" name="log" id="log">{{ old('log', $logPinjam->log) }}</textarea>
                @if($errors->has('log'))
                    <span class="text-danger">{{ $errors->first('log') }}</span>
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



@endsection