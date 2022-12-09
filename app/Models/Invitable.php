<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class Invitable extends Model
{
    // use SoftDeletes;
    use HasFactory;

    public const STATUS_SELECT = [
        'pending'  => 'pending',
        'accepted' => 'accepted',
        'rejected' => 'rejected',
    ];

    public $table = 'invitables';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'invitable_id',
        'invitable_type',
        'user_id',
        'invited_by_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public static function boot()
  {
      parent::boot();
      self::creating(function($row){
        $row->created_at=Carbon::now();
       });
      self::updating(function($row){
        $row->updated_at=Carbon::now();

       });

  }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invited_by()
    {
        return $this->belongsTo(User::class, 'invited_by_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
