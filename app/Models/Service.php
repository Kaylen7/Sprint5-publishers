<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;

    protected static function boot(){
        parent::boot();

        static::creating(function ($model){
            if(empty($model->uuid)){
                $model->uuid = Str::uuid()->toString();
            }
        });
    }
}
