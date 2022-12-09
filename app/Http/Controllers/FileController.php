<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Validator;
use ZipArchive;
use File;
use Carbon\Carbon;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Models\Media;
use App\Http\Controllers\Traits\FolderTrait;

class FileController extends Controller
{
  use MediaUploadingTrait,FolderTrait;
  public function rename($id ,Request $request){
    // dd($request->all());
    $validation = Validator::make($request->input(), [
      'name' => 'required',
      'id' =>'required|exists:media'
      ]);
      if ($validation->fails()) {
          $errors = json_decode($validation->errors());
          $errors_string = '';
          foreach ($errors as $error) {
              $errors_string .= $error[0] . "\n<br>";
          }
          return ['status' => 0, 'message' => $errors_string, 'data' => null];
      }
      $media=Media::find($id);
      if(!empty($media)){
          $media->update(["name"=>$request->name]);
          return ['status' => 1, 'message' => "success", 'data' => null];
      }
      return ['status' => 0, 'message' => "file not found", 'data' => null];
  }
  public function download($id ,Request $request){
      $media=Media::has("folder")->findOrFail($id);
      $folder=$media->folder;
      if($folder->user_id!=auth()->id()){
        $have_permission=$this->check_havin_access(auth()->user(),$folder,"folder");
        if(!$have_permission){
          return redirect()->route("shared.index")->withErrors([
                          'message' => 'unauthrized',
                      ]);
        }
      }
      if(!empty($media)){
         $headers = array(
                   'Content-Type: application/pdf',
                 );

         return response()->download($media->getPath(), $media->file_name, $headers);
      }
      return ['status' => 0, 'message' => "file not found", 'data' => null];
  }
  public function move($id,Request $request){

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
      $media=Media::find($id);
      if(!empty($media)){
        $media->update(["model_id"=>  $request->destination]);
        return ['status' => 1, 'message' => "success", 'data' => null];

      }
      return ['status' => 0, 'message' => "file not found", 'data' => null];

  }

  public function remove($id ,Request $request){
    $validation = Validator::make($request->input(), [
      'id' =>'required|exists:media'
      ]);
      if ($validation->fails()) {
          $errors = json_decode($validation->errors());
          $errors_string = '';
          foreach ($errors as $error) {
              $errors_string .= $error[0] . "\n<br>";
          }
          return ['status' => 0, 'message' => $errors_string, 'data' => null];
      }
      $media=Media::find($id);
      if(!empty($media)){
        $media->delete();
        return ['status' => 1, 'message' => "success", 'data' => null];

      }
      return ['status' => 0, 'message' => "file not found", 'data' => null];
  }
}
