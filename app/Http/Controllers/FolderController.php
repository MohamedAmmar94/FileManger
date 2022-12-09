<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Validator;
use ZipArchive;
use File;
use Carbon\Carbon;
use App\Http\Controllers\Traits\FolderTrait;
class FolderController extends Controller
{
  use FolderTrait;
  public $parent="";
  public $parent_id="";


    public function index()
    {
        $project =auth()->user()->project;
        // dd($project);
        return view('front.projects.index', compact('project'));
    }
    public function create()
    {
        return view('front.folders.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $folder = Folder::where('user_id', auth()->id() )->findOrFail($request->parent_id);
        $newFolder = Folder::create([
            'parent_id' => $request->parent_id,
            'name' => $request->input('name'),
            'project_id' => $folder->project_id,
        ]);
        return redirect()
            ->route('folders.show', [$newFolder])
            ->withStatus('New folder has been created');
    }

    public function show($id)
    {
        $folder = Folder::where("user_id",auth()->id())->findOrFail($id);
        return view('front.folders.show', compact('folder'));
    }

    public function upload()
    {
        return view('front.folders.upload');
    }

    public function storeMedia(Request $request)
    {
        if (request()->has('size')) {
            $this->validate(request(), [
                'file' => 'max:' . request()->input('size') * 1024,
            ]);
        }

        // If width or height is preset - we are validating it as an image
        if (request()->has('width') || request()->has('height')) {
            $this->validate(request(), [
                'file' => sprintf(
                    'image|dimensions:max_width=%s,max_height=%s',
                    request()->input('width', 100000),
                    request()->input('height', 100000)
                ),
            ]);
        }

        $path = storage_path('tmp/uploads');

        try {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        } catch (\Exception $e) {
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function postUpload(Request $request)
    {
      // dd($request->folder_id);
        $folder = Folder::where('user_id', auth()->id() )->findOrFail($request->folder_id);
        foreach ($request->input('files', []) as $file) {
          $folder->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('files');
        }
        // dd($folder);

        return redirect()->route('folders.show', $folder)->withStatus('Files has been uploaded');
    }
    public function rename($id ,Request $request){
      // dd($request->all());
      $validation = Validator::make($request->input(), [
        'name' => 'required',
        'id' =>'required|exists:folders'
        ]);
        if ($validation->fails()) {
            $errors = json_decode($validation->errors());
            $errors_string = '';
            foreach ($errors as $error) {
                $errors_string .= $error[0] . "\n<br>";
            }
            return ['status' => 0, 'message' => $errors_string, 'data' => null];
        }
        $folder=Folder::where("user_id",auth()->id())->find($id);
        if(!empty($folder)){
            $folder->update(["name"=>$request->name]);
            return ['status' => 1, 'message' => "success", 'data' => null];
        }
        return ['status' => 0, 'message' => "folder not found", 'data' => null];
    }
    public function reload_screen($id){
      $folder = Folder::where('user_id', auth()->id())->findOrFail($id);
          $view = view('front.folders.item', compact('folder'))->render();
        return response()->json(['status'=>1,'html' => $view]);
    }
    public function download_zip($id)
    {
      $folder = Folder::findOrFail($id);
      if($folder->user_id!=auth()->id()){
        $have_permission=$this->check_havin_access(auth()->user(),$folder,"folder");
        if(!$have_permission){
          return redirect()->route("shared.index")->withErrors([
                          'message' => 'unauthrized',
                      ]);
        }
      }
          $this->parent_id=$folder->id;
          $public_dir=public_path();
        	$zipFileName = $folder->name.'.zip';
          $zip = new ZipArchive();
          $categories_tree=[];
          if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
                foreach($folder->files as $key=>$file) {
                    $zip->addFile($file->getPath(), $file->file_name);
                }
                $categories_tree[$folder->id] = $this->load_folder_data($folder,$zip ,$folder->name);
                // dd($categories_tree);
            }
            $zip->close();
            $headers = array(
                  'Content-Type' => 'application/octet-stream',
              );
              $filetopath=$public_dir.'/'.$zipFileName;
              // dd($filetopath);
              if(file_exists($filetopath)){
                  return response()->download($filetopath,$zipFileName,$headers)->deleteFileAfterSend(true);
              }
    }
    public function display_in_modal($id,Request $request){
      // dd($id);

      $folder = Folder::where("user_id",auth()->id())->findOrFail($id);


          $current=isset($request->current)? $request->current : "";
           $view = view('front.folders.modal_item', compact('folder','current'))->render();
        return response()->json(['status'=>1,'html' => $view]);
    }
    public function move_folder($id,Request $request){

      $validation = Validator::make($request->input(), [
        'current' => 'required|exists:folders,id',
        'destination' =>'required|exists:folders,id'
        ]);
        if ($validation->fails()) {
            $errors = json_decode($validation->errors());
            $errors_string = '';
            foreach ($errors as $error) {
                $errors_string .= $error[0] . "\n<br>";
            }
            return ['status' => 0, 'message' => $errors_string, 'data' => null];
        }

      $folder = Folder::where("user_id",auth()->id())->findOrFail($id);
          $folder->update(["parent_id"=>  $request->destination]);
          return ['status' => 1, 'message' => "success", 'data' => null];
    }

    public function remove($id ,Request $request){
      $validation = Validator::make($request->input(), [
        'id' =>'required|exists:folders'
        ]);
        if ($validation->fails()) {
            $errors = json_decode($validation->errors());
            $errors_string = '';
            foreach ($errors as $error) {
                $errors_string .= $error[0] . "\n<br>";
            }
            return ['status' => 0, 'message' => $errors_string, 'data' => null];
        }
        $folder=Folder::find($id);
        if(!empty($folder)){
          $folders=Folder::whereIn("id",$this->get_subs_ids($id))->get();
          $folders->each->delete();

            return ['status' => 1, 'message' => "success", 'data' => null];
        }
        return ['status' => 0, 'message' => "folder not found", 'data' => null];
    }
}
