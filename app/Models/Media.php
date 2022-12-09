<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    use HasFactory;
    public function folder(){
      return $this->belongsTo(Folder::class, 'model_id','id');//->where("media.model_type","App\Models\Folder");

    }
    public function users()
    {
        return $this->morphToMany(User::class, 'invitable');
    }
}
