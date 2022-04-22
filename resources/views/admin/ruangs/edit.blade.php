@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.ruang.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ruangs.update", [$ruang->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="lantai_id">{{ trans('cruds.ruang.fields.lantai') }}</label>
                <select class="form-control select2 {{ $errors->has('lantai') ? 'is-invalid' : '' }}" name="lantai_id" id="lantai_id" required>
                    @foreach($lantais as $id => $entry)
                        <option value="{{ $id }}" {{ (old('lantai_id') ? old('lantai_id') : $ruang->lantai->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('lantai'))
                    <span class="text-danger">{{ $errors->first('lantai') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.ruang.fields.lantai_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.ruang.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $ruang->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.ruang.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="kapasitas">{{ trans('cruds.ruang.fields.kapasitas') }}</label>
                <input class="form-control {{ $errors->has('kapasitas') ? 'is-invalid' : '' }}" type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas', $ruang->kapasitas) }}" step="1" required>
                @if($errors->has('kapasitas'))
                    <span class="text-danger">{{ $errors->first('kapasitas') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.ruang.fields.kapasitas_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fasilitas">{{ trans('cruds.ruang.fields.fasilitas') }}</label>
                <textarea class="form-control {{ $errors->has('fasilitas') ? 'is-invalid' : '' }}" name="fasilitas" id="fasilitas">{{ old('fasilitas', $ruang->fasilitas) }}</textarea>
                @if($errors->has('fasilitas'))
                    <span class="text-danger">{{ $errors->first('fasilitas') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.ruang.fields.fasilitas_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="images">{{ trans('cruds.ruang.fields.images') }}</label>
                <div class="needsclick dropzone {{ $errors->has('images') ? 'is-invalid' : '' }}" id="images-dropzone">
                </div>
                @if($errors->has('images'))
                    <span class="text-danger">{{ $errors->first('images') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.ruang.fields.images_helper') }}</span>
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
    var uploadedImagesMap = {}
Dropzone.options.imagesDropzone = {
    url: '{{ route('admin.ruangs.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="images[]" value="' + response.name + '">')
      uploadedImagesMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedImagesMap[file.name]
      }
      $('form').find('input[name="images[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($ruang) && $ruang->images)
      var files = {!! json_encode($ruang->images) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="images[]" value="' + file.file_name + '">')
        }
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
