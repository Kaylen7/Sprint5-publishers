<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot(){
        parent::boot();

        static::creating(function ($model){
            if(empty($model->uuid)){
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    public function getProjectCount(): int{
        return Cache::remember("user_{$this->id}_project_count", 3600, function(){
            return $this->hasProjects()->count();
        });
    }

    public function updateProjectCount(): void{
        Cache::forget("user_{$this->id}_project_count");
        Cache::put("user_{$this->id}_project_count", $this->hasProjects()->count());
    }

    public function hasProjects(){
        return $this->hasMany(Project::class, 'owner_id');
    }
}
