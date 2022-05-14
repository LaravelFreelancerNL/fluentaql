<?php

declare(strict_types=1);

if (! function_exists('isStringable')) {

    function isStringable(mixed $value): bool
    {
        if ($value === null || is_scalar($value) || $value instanceof Stringable) {
            return true;
        }

        return false;
    }
}
