<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyFolderRequest;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Requests\UpdateFolderRequest;
use App\Models\Folder;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FoldersController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
   {
       abort_if(Gate::denies('folder_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

       if ($request->ajax()) {
           $query = Folder::with(['parent', 'user'])->select(sprintf('%s.*', (new Folder())->table));
           $table = Datatables::of($query);

           $table->addColumn('placeholder', '&nbsp;');
           $table->addColumn('actions', '&nbsp;');

           $table->editColumn('actions', function ($row) {
               $viewGate = 'folder_show';
               $editGate = 'folder_edit';
               $deleteGate = 'folder_delete';
               $crudRoutePart = 'folders';

               return view('partials.datatablesActions', compact(
               'viewGate',
               'editGate',
               'deleteGate',
               'crudRoutePart',
               'row'
           ));
           });

           $table->editColumn('id', function ($row) {
               return $row->id ? $row->id : '';
           });
           $table->editColumn('name', function ($row) {
               return $row->name ? $row->name : '';
           });
           $table->addColumn('parent_name', function ($row) {
               return $row->parent ? $row->parent->name : '';
           });

           $table->addColumn('user_name', function ($row) {
               return $row->user ? $row->user->name : '';
           });

           $table->rawColumns(['actions', 'placeholder', 'parent', 'user']);

           return $table->make(true);
       }

       return view('admin.folders.index');
   }


    public function create()
    {
        abort_if(Gate::denies('folder_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parents = Folder::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.folders.create', compact('parents', 'users'));
    }

    public function store(StoreFolderRequest $request)
    {
      if($request->parent==""){
        $root_count=Folder::where(["user_id"=>$request->user_id ,"parent_id" => null])->first();
        if(!empty($root_count) > 0){
          $request['parent_id']=$root_count->id;
        }
      }
        $folder = Folder::create($request->all());

        foreach ($request->input('files', []) as $file) {
            $folder->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $folder->id]);
        }

        return redirect()->route('admin.folders.index');
    }

    public function edit(Folder $folder)
    {
        abort_if(Gate::denies('folder_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parents = Folder::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $folder->load('parent', 'user');

        return view('admin.folders.edit', compact('folder', 'parents', 'users'));
    }

    public function update(UpdateFolderRequest $request, Folder $folder)
    {
        $folder->update($request->all());

        if (count($folder->files) > 0) {
            foreach ($folder->files as $media) {
                if (!in_array($media->file_name, $request->input('files', []))) {
                    $media->delete();
                }
            }
        }
        $media = $folder->files->pluck('file_name')->toArray();
        foreach ($request->input('files', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $folder->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files');
            }
        }

        return redirect()->route('admin.folders.index');
    }

    public function show(Folder $folder)
    {
        abort_if(Gate::denies('folder_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $folder->load('parent', 'user');

        return view('admin.folders.show', compact('folder'));
    }

    public function destroy(Folder $folder)
    {
        abort_if(Gate::denies('folder_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $folder->delete();

        return back();
    }

    public function massDestroy(MassDestroyFolderRequest $request)
    {
        Folder::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('folder_create') && Gate::denies('folder_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Folder();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
