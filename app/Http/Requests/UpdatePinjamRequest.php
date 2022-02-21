<?php

namespace App\Http\Requests;

use App\Models\Pinjam;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePinjamRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('pinjam_edit');
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
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'time_end' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'penggunaan' => [
                'required',
            ],
        ];
    }
}
