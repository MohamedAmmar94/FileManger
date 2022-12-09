<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Invitable,Folder,User};
use App\Http\Controllers\Traits\FolderTrait;
use ZipArchive;

class SharedController extends Controller
{
  use FolderTrait;
    public function index(){
      $shared_folders=auth()->user()->invitable_folders()->get()->sortBy("parents_count");
      $shared_arr=[];
      $shared=[];
      foreach ($shared_folders as $key => $row) {
        $flag=1;
        foreach($row->parents_arr as $parent){
          if(in_array($parent,$shared_arr)){
              $flag=0;
          }
        }
        if($flag==1){
          $shared[]=$row;
          $shared_arr[]=$row->id;
        }
      }
      $shared_folders=$shared;
      $shared_files=auth()->user()->invitable_media;
      return view("front.shared.index",compact('shared_folders','shared_files'));
    }
    public function show($id){

      $folder=Folder::findOrFail($id);
      $have_permission=$this->check_havin_access(auth()->user(),$folder,"folder");
      if(!$have_permission){
        return redirect()->route("shared.index")->withErrors([
                        'message' => 'unauthrized',
                    ]);
      }
      $shared_folders=$folder->children;
      $shared_files=$folder->files;
      return view("front.shared.index",compact('shared_folders','shared_files','folder'));

    }
    public function download_zip($id)
    {
      $folder = Folder::findOrFail($id);
      $have_permission=$this->check_havin_access(auth()->user(),$folder,"folder");
      if(!$have_permission){
        return redirect()->route("shared.index")->withErrors([
                        'message' => 'unauthrized',
                    ]);
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
              if(file_exists($filetopath)){
                  return response()->download($filetopath,$zipFileName,$headers)->deleteFileAfterSend(true);
              }
    }
}
