<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
class ServiceValidationHelper
{
    public static function isOneLevelArray(array $array): bool{
        return collect($array)->every(fn($item) => is_string($item));
    }

    public static function isValidTranslatingLanguage($language): bool{
        return is_array($language) &&
        Arr::has($language, ['source', 'target', 'bidirectional']) &&
        collect(['source', 'target'])->every(fn ($key) => is_string($language[$key])) &&
        is_bool($language['bidirectional']);
    }
}
