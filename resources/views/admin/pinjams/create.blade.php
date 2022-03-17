@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.pinjam.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.pinjams.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="ruang_id">{{ trans('cruds.pinjam.fields.ruang') }}</label>
                <select class="form-control select2 {{ $errors->has('ruang') ? 'is-invalid' : '' }}" name="ruang_id" id="ruang_id" required>
                    @foreach($ruangs as $id => $entry)
                        <option value="{{ $id }}" {{ old('ruang_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('ruang'))
                    <span class="text-danger">{{ $errors->first('ruang') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.pinjam.fields.ruang_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="time_start">{{ trans('cruds.pinjam.fields.time_start') }}</label>
                <input class="form-control datetime {{ $errors->has('time_start') ? 'is-invalid' : '' }}" type="text" name="time_start" id="time_start" value="{{ old('time_start') }}" required>
                @if($errors->has('time_start'))
                    <span class="text-danger">{{ $errors->first('time_start') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.pinjam.fields.time_start_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="time_end">{{ trans('cruds.pinjam.fields.time_end') }}</label>
                <input class="form-control datetime {{ $errors->has('time_end') ? 'is-invalid' : '' }}" type="text" name="time_end" id="time_end" value="{{ old('time_end') }}" required>
                @if($errors->has('time_end'))
                    <span class="text-danger">{{ $errors->first('time_end') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.pinjam.fields.time_end_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="no_hp">{{ trans('cruds.pinjam.fields.no_hp') }}</label>
                <input class="form-control {{ $errors->has('no_hp') ? 'is-invalid' : '' }}" type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', '') }}" required>
                @if($errors->has('no_hp'))
                    <span class="text-danger">{{ $errors->first('no_hp') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.pinjam.fields.no_hp_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="penggunaan">{{ trans('cruds.pinjam.fields.penggunaan') }}</label>
                <textarea class="form-control {{ $errors->has('penggunaan') ? 'is-invalid' : '' }}" name="penggunaan" id="penggunaan" required>{{ old('penggunaan') }}</textarea>
                @if($errors->has('penggunaan'))
                    <span class="text-danger">{{ $errors->first('penggunaan') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.pinjam.fields.penggunaan_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.pinjam.fields.unit_pengguna') }}</label>
                <select class="form-control {{ $errors->has('unit_pengguna') ? 'is-invalid' : '' }}" name="unit_pengguna" id="unit_pengguna">
                    <option value disabled {{ old('unit_pengguna', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Pinjam::UNIT_PENGGUNA_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('unit_pengguna', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('unit_pengguna'))
                    <span class="text-danger">{{ $errors->first('unit_pengguna') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.pinjam.fields.unit_pengguna_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="surat_pengajuan">{{ trans('cruds.pinjam.fields.surat_pengajuan') }}</label>
                <div class="needsclick dropzone {{ $errors->has('surat_pengajuan') ? 'is-invalid' : '' }}" id="surat_pengajuan-dropzone">
                </div>
                @if($errors->has('surat_pengajuan'))
                    <span class="text-danger">{{ $errors->first('surat_pengajuan') }}</span>
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



@endsection

@section('scripts')
<script>
    Dropzone.options.suratPengajuanDropzone = {
    url: '{{ route('admin.pinjams.storeMedia') }}',
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