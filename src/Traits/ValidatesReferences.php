<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Traits;

trait ValidatesReferences
{
    public function isBind(mixed $value): bool
    {
        if (is_string($value)) {
            return true;
        }
        if (is_object($value)) {
            return true;
        }

        return false;
    }

    public function isCollectionBind(mixed $value): bool
    {
        if (is_string($value)) {
            return true;
        }

        return false;
    }

    public function isVariable(mixed $value): bool
    {
        if (is_string($value) && preg_match('/^\$?[a-zA-Z_][a-zA-Z0-9_]*+$/', $value)) {
            return true;
        }

        return false;
    }

    /**
     * @param  array<array-key, mixed>  $registeredVariables
     */
    public function isRegisteredVariable(
        int|string $value,
        array $registeredVariables = []
    ): bool {
        return isset($registeredVariables[$value]);
    }

    public function isAttribute(mixed $value): bool
    {
        $pattern = '/^(@?[\d\w_]+|`@?[\d\w_]+`)(\[\`.+\`\]|\[[\d\w\*]*\])*'
            .'(\.(\`.+\`|@?[\d\w]*)(\[\`.+\`\]|\[[\d\w\*]*\])*)*$/';
        if (
            is_string($value) &&
            preg_match($pattern, $value)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param  array<array-key, null|object|scalar>  $registeredVariables
     */
    public function isReference(
        mixed $value,
        array $registeredVariables = []
    ): bool {
        /** @psalm-suppress  ArgumentTypeCoercion */
        $variables = implode('|', $registeredVariables);

        if (! is_string($value) || empty($value)) {
            return false;
        }

        return (bool) preg_match(
            '/^('
            .$variables
            .'|CURRENT|NEW|OLD)(\[\`.+\`\]|\[[\d\w\*]*\])*(\.(\`.+\`|@?[\d\w]*)(\[\`.+\`\]|\[[\d\w\*]*\])*)*$/',
            $value
        );
    }
}
