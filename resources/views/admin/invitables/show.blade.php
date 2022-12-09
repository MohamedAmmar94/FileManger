@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.invitable.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.invitables.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.invitable.fields.id') }}
                        </th>
                        <td>
                            {{ $invitable->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.invitable.fields.invitable') }}
                        </th>
                        <td>
                            {{ $invitable->invitable }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.invitable.fields.invitable_type') }}
                        </th>
                        <td>
                            {{ $invitable->invitable_type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.invitable.fields.user') }}
                        </th>
                        <td>
                            {{ $invitable->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.invitable.fields.invited_by') }}
                        </th>
                        <td>
                            {{ $invitable->invited_by->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.invitable.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Invitable::STATUS_SELECT[$invitable->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.invitables.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection