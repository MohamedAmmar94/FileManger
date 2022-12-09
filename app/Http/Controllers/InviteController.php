<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Invitable,Folder,User};
use App\Http\Controllers\Traits\FolderTrait;
use App\Models\Media;
use Validator;
use Carbon\Carbon;
class InviteController extends Controller
{
  use FolderTrait;
    public function sent(Request $request){
      $validation = Validator::make($request->input(), [
        'email' =>'required|email|exists:users,email',
        'type' =>'required|in:folder,file',
        'id' =>'required',
        ]);
        if ($validation->fails()) {
            $errors = json_decode($validation->errors());
            $errors_string = '';
            foreach ($errors as $error) {
                $errors_string .= $error[0] . "\n<br>";
            }
            return ['status' => 0, 'message' => $errors_string, 'data' => null];
        }
        $user=User::where("email",$request->email)->first();
        if(empty($user)){
          return ['status' => 0, 'message' => 'user not found', 'data' => null];
        }
        if(auth()->id()==$user->id){
          return ['status' => 0, 'message' => 'you already having access to this '.$request->type, 'data' => null];

        }
        if($request->type=="folder"){
          $folder=Folder::where(["id"=>$request->id,"user_id"=>auth()->id()])->first();
          if(!empty($folder)){
            //===============>check have access <========================//
            $have_permission=$this->check_havin_access($user,$folder,"folder");
            // dd($have_permission);
            if($have_permission){
              return ['status' => 0, 'message' => 'this user  already having access to this '.$request->type, 'data' => null];
            }

            //===============>sent invetation <========================//
            $data=[];
            $data['name']=$folder->name;
            $data['type']="folder";
            \Mail::to($user->email)->send(new \App\Mail\InvitationMail(auth()->user(),$user,$data));
            $user->invitable_folders()->attach($folder,["status"=>"pending","invited_by_id"=>auth()->id(),'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);

            return ['status' => 1, 'message' => "invetation sent successfully", 'data' => null];
          }
          return ['status' => 0, 'message' => "Folder not found", 'data' => null];
        }else{
          $media=Media::where(["id"=>$request->id,"model_type"=>"App\Models\Folder"])->whereHas("folder")->first();
          if(!empty($media)){
            //===============>check have access <========================//

            $have_permission=$this->check_havin_access($user,$media,"file");
            if($have_permission){
              return ['status' => 0, 'message' => 'this user  already having access to this '.$request->type, 'data' => null];
            }

            //===============>sent invetation <========================//
            $data=[];
            $data['name']=$media->name;
            $data['type']="file";
            \Mail::to($user->email)->send(new \App\Mail\InvitationMail(auth()->user(),$user,$data));
            $user->invitable_media()->attach($media,["status"=>"pending","invited_by_id"=>auth()->id(),'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);

            return ['status' => 1, 'message' => "invetation sent successfully", 'data' => null];
          }
          return ['status' => 0, 'message' => "Media not found", 'data' => null];
        }
          // $this->sent_invetation($request->email);
          //============>sent Invettion email < ======================//
          return ['status' => 1, 'message' => "invetation sent successfully", 'data' => null];
    }
    public function get_allawed_users(Request $request){
      $validation = Validator::make($request->input(), [
        'type' =>'required|in:folder,file',
        'id' =>'required',
        ]);
        if ($validation->fails()) {
            $errors = json_decode($validation->errors());
            $errors_string = '';
            foreach ($errors as $error) {
                $errors_string .= $error[0] . "\n<br>";
            }
            return ['status' => 0, 'message' => $errors_string, 'data' => null];
        }
        $invites_for_file=[];
        if($request->type=="file"){
          $row=Media::findOrFail($request->id);
          // $type="App\Models\Media";
          $invites_for_file=Invitable::where(["invitable_type"=>"App\Models\Media","invitable_id"=>$row->id])->pluck("user_id")->toArray();
          $folder=$row->folder;
        }else{
          $folder=Folder::findOrFail($request->id);
          $type="App\Models\Folder";
        }
        $parents_arr=$folder->parents_arr;
        $parents_arr[]=$folder->id;

        // dd($parents_arr,$invites_for_file);
        $invites=Invitable::where("invitable_type","App\Models\Folder")->whereIn("invitable_id",$parents_arr)->pluck("user_id")->toArray();
        $invites=array_merge($invites,$invites_for_file);
        $users=User::whereIn("id",$invites)->get();
        $id=$request->id;
        $type=$request->type;

        $view = view('front.folders.allowed_users_list', compact('users','id','type'))->render();
        return response()->json(['status'=>1,'html' => $view]);

    }
    public function delete_allowed(Request $request){
      $validation = Validator::make($request->input(), [
        'type' =>'required|in:folder,file',
        'id' =>'required',
        'user_id' =>'required|exists:users,id',
        ]);
        if ($validation->fails()) {
            $errors = json_decode($validation->errors());
            $errors_string = '';
            foreach ($errors as $error) {
                $errors_string .= $error[0] . "\n<br>";
            }
            return ['status' => 0, 'message' => $errors_string, 'data' => null];
        }
        $invites_for_file=[];
        if($request->type=="file"){
          $row=Media::findOrFail($request->id);
          $invites=Invitable::where(["invitable_type"=>"App\Models\Media","invitable_id"=>$row->id,"user_id"=>$request->user_id])->first();
          if(!empty($invites)){
            $invites->delete();
            return ['status' => 1, 'message' => "user deleted from access this file successfully", 'data' => null];
          }
          $folder=$row->folder;
        }else{
          $folder=Folder::findOrFail($request->id);
          $type="App\Models\Folder";
        }
        $parents_arr=$folder->parents_arr;
        $parents_arr[]=$folder->id;
        $invites=Invitable::where(["invitable_type"=>"App\Models\Folder","user_id"=>$request->user_id])->whereIn("invitable_id",$parents_arr)->first();
        if(!empty($invites)){
          $invites->delete();
          return ['status' => 1, 'message' => "user deleted from access this folder successfully", 'data' => null];
        }
        return ['status' => 0, 'message' => "user access not found", 'data' => null];
    }
}
