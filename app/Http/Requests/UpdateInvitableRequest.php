<?php

namespace App\Http\Requests;

use App\Models\Invitable;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateInvitableRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('invitable_edit');
    }

    public function rules()
    {
        return [
            'invitable' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'invitable_type' => [
                'string',
                'required',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
            'invited_by_id' => [
                'required',
                'integer',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
