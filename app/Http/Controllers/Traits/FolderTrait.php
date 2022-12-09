<?php

namespace App\Http\Controllers\Traits;
use App\Models\{Folder,Invitable,User};
use Illuminate\Http\Request;
use Validator;
use ZipArchive;
use File;
use Carbon\Carbon;
trait FolderTrait
{
  public function load_folder_data($folder,ZipArchive $zip,$parent=""){
    if($folder->id==$this->parent_id){
      $this->parent="";
    }elseif($folder->parent_id==$this->parent_id){
      $this->parent=$folder->name."/";
    }else{
      $this->parent=($this->parent != "") ? $this->parent.$parent."/" : $parent."/";
    }
    $parent=$this->parent;

    $sub_parents=[];
    $childs = Folder::where("parent_id",$folder->id)->get();
    if(count($childs) >0){
      foreach($childs as $child){
        $sub_parents[$child->id]=$this->load_folder_data($child, $zip,$child->name,$parent);
        $url=$parent.$child->name;
        $sub_parents[$child->id]['url']=$url;
        $zip->addEmptyDir($url);
        foreach($child->files as $key=>$file) {
          $zip->addFile($file->getPath(), $url."/".$file->file_name);
        }
      }
      $sub_parents["pareent"]=$parent;
      return $sub_parents;
    }else{
      if($parent==""){
        $zip->addEmptyDir($parent.$folder->name);
      }

    }
    return [];

  }
  public function get_subs_ids($id,$arr=[])
  {
    array_push($arr,$id);
    $childs = Folder::where("parent_id",$id)->pluck("id")->toArray();
      foreach($childs as $child){
        $ids=$this->get_subs_ids($child, $arr);
        $arr=array_unique(array_merge($arr,$ids), SORT_REGULAR);
      }
      return $arr;
  }
  public function check_havin_access($user,$row,$type){
    $folder=$row;
    if($type=="file"){
      $invite=Invitable::where(["user_id"=>$user->id,"invitable_type"=>"App\Models\Media","invitable_id"=>$row->id])->first();
      if(!empty($invite)){
        return true;
      }
      $folder=$row->folder;
    }
    $invite=Invitable::where(["user_id"=>$user->id,"invitable_type"=>"App\Models\Folder"])
    ->where(function($q) use($folder){
      $q->where("invitable_id",$folder->id)->orWhereIn("invitable_id",$folder->parents_arr);
    } )
    ->first();
    return !empty($invite);
  }
}
