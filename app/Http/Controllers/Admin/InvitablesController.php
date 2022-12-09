<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyInvitableRequest;
use App\Http\Requests\StoreInvitableRequest;
use App\Http\Requests\UpdateInvitableRequest;
use App\Models\Invitable;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InvitablesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('invitable_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invitables = Invitable::with(['user', 'invited_by'])->get();

        return view('admin.invitables.index', compact('invitables'));
    }

    public function create()
    {
        abort_if(Gate::denies('invitable_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $invited_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.invitables.create', compact('invited_bies', 'users'));
    }

    public function store(StoreInvitableRequest $request)
    {
        $invitable = Invitable::create($request->all());

        return redirect()->route('admin.invitables.index');
    }

    public function edit(Invitable $invitable)
    {
        abort_if(Gate::denies('invitable_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $invited_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $invitable->load('user', 'invited_by');

        return view('admin.invitables.edit', compact('invitable', 'invited_bies', 'users'));
    }

    public function update(UpdateInvitableRequest $request, Invitable $invitable)
    {
        $invitable->update($request->all());

        return redirect()->route('admin.invitables.index');
    }

    public function show(Invitable $invitable)
    {
        abort_if(Gate::denies('invitable_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invitable->load('user', 'invited_by');

        return view('admin.invitables.show', compact('invitable'));
    }

    public function destroy(Invitable $invitable)
    {
        abort_if(Gate::denies('invitable_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invitable->delete();

        return back();
    }

    public function massDestroy(MassDestroyInvitableRequest $request)
    {
        Invitable::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
