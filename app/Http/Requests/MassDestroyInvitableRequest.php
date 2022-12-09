<?php

namespace App\Http\Requests;

use App\Models\Invitable;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyInvitableRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('invitable_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:invitables,id',
        ];
    }
}
