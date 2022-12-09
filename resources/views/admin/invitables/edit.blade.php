@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.invitable.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.invitables.update", [$invitable->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="invitable">{{ trans('cruds.invitable.fields.invitable') }}</label>
                <input class="form-control {{ $errors->has('invitable') ? 'is-invalid' : '' }}" type="number" name="invitable" id="invitable" value="{{ old('invitable', $invitable->invitable) }}" step="1">
                @if($errors->has('invitable'))
                    <div class="invalid-feedback">
                        {{ $errors->first('invitable') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.invitable.fields.invitable_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="invitable_type">{{ trans('cruds.invitable.fields.invitable_type') }}</label>
                <input class="form-control {{ $errors->has('invitable_type') ? 'is-invalid' : '' }}" type="text" name="invitable_type" id="invitable_type" value="{{ old('invitable_type', $invitable->invitable_type) }}" required>
                @if($errors->has('invitable_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('invitable_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.invitable.fields.invitable_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.invitable.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $invitable->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.invitable.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="invited_by_id">{{ trans('cruds.invitable.fields.invited_by') }}</label>
                <select class="form-control select2 {{ $errors->has('invited_by') ? 'is-invalid' : '' }}" name="invited_by_id" id="invited_by_id" required>
                    @foreach($invited_bies as $id => $entry)
                        <option value="{{ $id }}" {{ (old('invited_by_id') ? old('invited_by_id') : $invitable->invited_by->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('invited_by'))
                    <div class="invalid-feedback">
                        {{ $errors->first('invited_by') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.invitable.fields.invited_by_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.invitable.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Invitable::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $invitable->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.invitable.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection