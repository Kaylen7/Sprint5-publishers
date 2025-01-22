<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidServiceLanguage implements ValidationRule
{
    public function __construct(
        private ?string $type
    ){}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->type === "proofreading" && !ServiceValidationHelper::isOneLevelArray($value)){
            $fail("$attribute must be a one-level language pair array. For example: ['es-ES', 'en-UK', 'de-DE']");
        } elseif($this->type === "translating"){
            foreach($value as $language){
                if(!ServiceValidationHelper::isValidTranslatingLanguage($language)){
                    $fail("$attribute Language JSON must have structure: ['source': 'es-ES', 'target': 'ca-ES', 'bidirectional': true]");
                }
            }
        }
    }
}
