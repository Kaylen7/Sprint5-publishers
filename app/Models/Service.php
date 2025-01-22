<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ServiceValidationHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;

    protected $fillable = [
        'type',
        'languages',
        'name',
        'description',
        'available',
        'user_id'
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function ($model){
            if(empty($model->uuid)){
                $model->uuid = Str::uuid()->toString();
            }

            if($model->type === "proofreading" || $model->type === "translating"){
                $languages = $model->languages;

                if (is_string($languages)){
                    $languages = json_decode($languages, true);
                }

                $languages = collect($languages)->unique(function($item){
                    return is_array($item) ? json_encode($item) : $item;
                })->values()->toArray();

                $model->languages = $languages;
            }
        });

        static::saving(function($model){
            if (!$model->name){
                $user = User::findOrFail($model->user_id);
                $model->name = $user->name;
            }
            if (!$model->description){
                $user = User::findOrFail($model->user_id);
                $model->description = $model->type . " service of " . $user->name;
            }
            if ($model->type === 'proofreading'){
                if (!is_array($model->languages) || count($model->languages) === 0 || !ServiceValidationHelper::isOneLevelArray($model->languages)){
                    throw new \Exception("Languages must be a one-level language pair array. For example: ['es-ES', 'en-UK', 'de-DE']");
                }
            }

            if($model->type === 'translating'){
                if(!is_array($model->languages) || count($model->languages) === 0){
                    throw new \Exception("Languages must be a valid JSON object.");
                }

                foreach($model->languages as $language){
                    if (!ServiceValidationHelper::isValidTranslatingLanguage($language)){
                        throw new \Exception("Language JSON must have structure: ['source': 'es-ES', 'target': 'ca-ES', 'bidirectional': true]");
                    }
                }
            }

            if($model->type === 'proofreading' || $model->type === 'translating'){
                $languages = collect($model->languages)->unique(function($item){
                    return is_array($item) ? json_encode($item) : $item;
                })->values()->toArray();

                $model->languages = $languages;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'languages' => 'json',
        ];
    }
}
