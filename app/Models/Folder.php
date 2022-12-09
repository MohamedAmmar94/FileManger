<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Folder extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;
    use HasFactory;

    public $table = 'folders';

    protected $appends = [
        'files',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',

        'parent_id',
        'user_id',
        'thumbnail_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public static function boot()
  {
      parent::boot();
      self::creating(function($folder){
        if( empty($folder->user_id)){

          $folder->user_id=$folder->pre_parent()->user_id;
        }
        // dd($folder);
       });
      self::created(function($folder){
        // dd($folder->project->user);
        // if(!empty($folder->project) && isset($folder->project->user)&& !empty($folder->project->user)){
        //
        //   $folder->user_id=$folder->project->user->id;
        // }
       });
      static::deleting(function ($folder) {
        if (count($folder->files) > 0) {
            foreach ($folder->files as $media) {
                    $media->delete();
            }
        }
       });
  }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    // public function project()
    // {
    //     return $this->belongsTo(Project::class, 'project_id');
    // }

    public function getFilesAttribute()
    {
      $image_ext=["jpeg","jpg","png","gif"];
      foreach ($this->getMedia('files') as $key => $file) {
        if(in_array($file->extension,$image_ext)){

          // $file->cover=$file->getUrl();
          $file->type= 'image';
        }elseif($file->extension=="pdf"){
          $file->type= 'pdf';
        }else{
          $file->type= 'other';
          // $file->cover=  url('images/file-thumbnail.png');
          // $file->cover=$file->getUrl();

        }
      }
      // dd($this->getMedia('files'));
        return $this->getMedia('files');
    }

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }
    public function pre_parent()
    {
      $parent=$this->parent;
      while($parent->parent_id!=null){
        $parent=$parent->parent;
      }
        return $parent;
    }
    public function getParentsArrAttribute()
    {
      $arr=[];
      $parent=$this->parent;
      if(!empty($parent)){
        while($parent->parent_id!=null){
          $arr[]=$parent->id;
          $parent=$parent->parent;
        }
        $arr[]=$parent->id;
      }
        return $arr;
    }
    public function getParentsCountAttribute()
    {
      $count=0;
      $parent=$this->parent;
      while($parent->parent_id!=null){
        $count ++;
        $parent=$parent->parent;
      }

        return $count;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function children()
    {
      return $this->hasMany(Folder::class, 'parent_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function has_permission(){
      if($this->user_id==auth()->id()){
        return true;
      }
      return false;
    }
    public function users()
    {
        return $this->morphToMany(User::class, 'invitable');
    }
}
