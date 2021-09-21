<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

trait ValidatesReferences
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isBind($value): bool
    {
        if (is_string($value)) {
            return true;
        }
        if (is_object($value)) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isCollectionBind($value): bool
    {
        if (is_string($value)) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isVariable($value): bool
    {
        if (is_string($value) && preg_match('/^\$?[a-zA-Z_][a-zA-Z0-9_]*+$/', $value)) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed  $value
     * @param  array  $registeredVariables
     * @return bool
     */
    public function isRegisteredVariable($value, $registeredVariables = []): bool
    {
        return isset($registeredVariables[$value]);
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isAttribute($value): bool
    {
        $pattern = '/^(@?[\d\w_]+|`@?[\d\w_]+`)(\[\`.+\`\]|\[[\d\w\*]*\])*'
            . '(\.(\`.+\`|@?[\d\w]*)(\[\`.+\`\]|\[[\d\w\*]*\])*)*$/';
        if (
            is_string($value) &&
            preg_match($pattern, $value)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed $value
     * @param array $registeredVariables
     * @return bool
     */
    public function isReference($value, $registeredVariables = []): bool
    {
        $variables = '';
        if (!empty($registeredVariables)) {
            $variables = implode('|', $registeredVariables);
        }

        if (! is_string($value) || empty($value)) {
            return false;
        }

        return (bool) preg_match(
            '/^('
            . $variables
            . '|CURRENT|NEW|OLD)(\[\`.+\`\]|\[[\d\w\*]*\])*(\.(\`.+\`|@?[\d\w]*)(\[\`.+\`\]|\[[\d\w\*]*\])*)*$/',
            $value
        );
    }
}
