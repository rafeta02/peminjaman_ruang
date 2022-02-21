@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ruang.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ruangs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ruang.fields.id') }}
                        </th>
                        <td>
                            {{ $ruang->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ruang.fields.lantai') }}
                        </th>
                        <td>
                            {{ $ruang->lantai->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ruang.fields.name') }}
                        </th>
                        <td>
                            {{ $ruang->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ruang.fields.kapasitas') }}
                        </th>
                        <td>
                            {{ $ruang->kapasitas }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ruangs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection