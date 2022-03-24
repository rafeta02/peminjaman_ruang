<?php

namespace App\Http\Requests;

use App\Models\Pinjam;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePinjamRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('pinjam_create');
    }

    public function rules()
    {
        return [
            'ruang_id' => [
                'required',
                'integer',
            ],
            'time_start' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.clock_format'),
            ],
            'time_end' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.clock_format'),
            ],
            'no_hp' => [
                'string',
                'required',
            ],
            'penggunaan' => [
                'required',
            ],
        ];
    }
}
