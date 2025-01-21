<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected const CHARS_PAG = 1800;

    protected $fillable = [
        'name',
        'description',
        'num_chars',
        'owner_id',
        'status',
        'start_date'
    ];

    protected $guarded = [
        'owner_id',
        'num_pages',
        'total_price',
        'projected_end_date'
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function ($model){
            if(empty($model->uuid)){
                $model->uuid = Str::uuid()->toString();
            }

            if(empty($model->num_pages)){
                $model->num_pages = floor($model->num_chars / self::CHARS_PAG);
            }

            if(empty($model->projected_end_date)){
                $model->projected_end_date = $model->start_date;
            }
        });

        static::created(function($model){
            $owner = $model->owner;
            if($owner){
                $owner->updateProjectCount();
            }
        });

        static::saving(function ($model){
            if($model->start_date < now()->format('Y-m-d')){
                throw new \Exception("Can't open a portal to the past yet...");
            }
        });

        static::updating(function($model){
            if($model->isDirty('num_chars')){
                $model->num_pages = floor($model->num_chars / self::CHARS_PAG);
            }
        });
    }

    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }
}