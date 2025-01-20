<?php

namespace App\Rules;

use Closure;
use App\Models\Project;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueProject implements ValidationRule
{
    public function __construct(
        private int $userId
        ){}
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $project = Project::where('owner_id', $this->userId)
        ->where('name', request('name'))
        ->where('description', request('description'))
        ->where('num_chars', request('num_chars'))
        ->whereDate('start_date', request('start_date'))
        ->first();

        if($project){
            $fail('A similar project already exists with these details.');
        }
    }
}
