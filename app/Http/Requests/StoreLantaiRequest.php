<?php

namespace App\Http\Requests;

use App\Models\Lantai;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreLantaiRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('lantai_create');
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
