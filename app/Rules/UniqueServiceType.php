<?php

namespace App\Rules;

use Closure;
use App\Models\User;
use App\Models\Service;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueServiceType implements ValidationRule
{
    public function __construct(
        private User $user,
        private ?int $target_id
    ){}
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->user->hasRole('admin') && $this->target_id){
            $userHasServiceType = Service::where('type', $value)
            ->where('user_id', $this->target_id)
            ->exists();
        } else {
            $userHasServiceType = Service::where('type', $value)
            ->where('user_id', $this->user->id)
            ->exists();
        }
        
        if($userHasServiceType){
            $fail("You already have a service of $attribute: $value");
        }
    }
}
