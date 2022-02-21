<?php

namespace App\Http\Requests;

use App\Models\Lantai;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLantaiRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('lantai_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
        ];
    }
}
