@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.pinjam.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.pinjams.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="ruang_id">{{ trans('cruds.pinjam.fields.ruang') }}</label>
                            <select class="form-control select2" name="ruang_id" id="ruang_id" required>
                                @foreach($ruangs as $id => $entry)
                                    <option value="{{ $id }}" {{ old('ruang_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('ruang'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('ruang') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pinjam.fields.ruang_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="time_start">{{ trans('cruds.pinjam.fields.time_start') }}</label>
                            <input class="form-control datetime" type="text" name="time_start" id="time_start" value="{{ old('time_start') }}" required>
                            @if($errors->has('time_start'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('time_start') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pinjam.fields.time_start_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="time_end">{{ trans('cruds.pinjam.fields.time_end') }}</label>
                            <input class="form-control datetime" type="text" name="time_end" id="time_end" value="{{ old('time_end') }}" required>
                            @if($errors->has('time_end'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('time_end') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pinjam.fields.time_end_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="no_hp">{{ trans('cruds.pinjam.fields.no_hp') }}</label>
                            <input class="form-control" type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', '') }}" required>
                            @if($errors->has('no_hp'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('no_hp') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pinjam.fields.no_hp_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="penggunaan">{{ trans('cruds.pinjam.fields.penggunaan') }}</label>
                            <textarea class="form-control" name="penggunaan" id="penggunaan" required>{{ old('penggunaan') }}</textarea>
                            @if($errors->has('penggunaan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('penggunaan') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pinjam.fields.penggunaan_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.pinjam.fields.unit_pengguna') }}</label>
                            <select class="form-control" name="unit_pengguna" id="unit_pengguna">
                                <option value disabled {{ old('unit_pengguna', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Pinjam::UNIT_PENGGUNA_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('unit_pengguna', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('unit_pengguna'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('unit_pengguna') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pinjam.fields.unit_pengguna_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="surat_pengajuan">{{ trans('cruds.pinjam.fields.surat_pengajuan') }}</label>
                            <div class="needsclick dropzone" id="surat_pengajuan-dropzone">
                            </div>
                            @if($errors->has('surat_pengajuan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('surat_pengajuan') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pinjam.fields.surat_pengajuan_helper') }}</span>
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

@section('scripts')
<script>
    Dropzone.options.suratPengajuanDropzone = {
    url: '{{ route('frontend.pinjams.storeMedia') }}',
    maxFilesize: 2, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').find('input[name="surat_pengajuan"]').remove()
      $('form').append('<input type="hidden" name="surat_pengajuan" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="surat_pengajuan"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($pinjam) && $pinjam->surat_pengajuan)
      var file = {!! json_encode($pinjam->surat_pengajuan) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="surat_pengajuan" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection