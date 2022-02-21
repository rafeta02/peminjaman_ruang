@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.lantai.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.lantais.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.lantai.fields.id') }}
                        </th>
                        <td>
                            {{ $lantai->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lantai.fields.name') }}
                        </th>
                        <td>
                            {{ $lantai->name }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.lantais.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#lantai_ruangs" role="tab" data-toggle="tab">
                {{ trans('cruds.ruang.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="lantai_ruangs">
            @includeIf('admin.lantais.relationships.lantaiRuangs', ['ruangs' => $lantai->lantaiRuangs])
        </div>
    </div>
</div>

@endsection