<?php

namespace App\Http\Requests;

use App\Models\Lantai;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyLantaiRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('lantai_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:lantais,id',
        ];
    }
}
