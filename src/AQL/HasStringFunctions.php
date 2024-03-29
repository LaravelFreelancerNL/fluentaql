<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Geo AQL functions.
 *
 * @see https://www.arangodb.com/docs/stable/aql/functions-string.html
 */
trait HasStringFunctions
{
    /**
     * Concatenate the values passed as value1 to valueN.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#concat
     */
    public function concat(
        string|object ...$arguments
    ): FunctionExpression {
        return new FunctionExpression('CONCAT', $arguments);
    }

    /**
     * Concatenate the strings passed as arguments value1 to valueN using the separator string.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#concat_separator
     *
     * @param  array<int, string|array<string>|Expression|QueryBuilder>  ...$values
     */
    public function concatSeparator(
        string|Expression|QueryBuilder $separator,
        string|array|Expression|QueryBuilder ...$values
    ): FunctionExpression {
        $arguments = array_merge([$separator], $values);

        return new FunctionExpression('CONCAT_SEPARATOR', $arguments);
    }

    /**
     * Check whether the string search is contained in the string text.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#contains
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function contains(
        string|object $text,
        string|object $search,
        bool|object $returnIndex = false
    ): FunctionExpression {
        return new FunctionExpression('CONTAINS', [$text, $search, $returnIndex]);
    }

    /**
     * Return the position of the first occurrence of the string search inside the string text. Positions start at 0.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#find_first
     */
    public function findFirst(
        string|object $text,
        string|object $search,
        int|object $start = null,
        int|object $end = null
    ): FunctionExpression {
        $arguments = [
            'text' => $text,
            'search' => $search,
        ];
        if (isset($start)) {
            $arguments['start'] = $start;
        }
        if (isset($end)) {
            $arguments['end'] = $end;
        }

        return new FunctionExpression('FIND_FIRST', $arguments);
    }

    /**
     * Return the position of the last occurrence of the string search inside the string text. Positions start at 0.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#find_last
     */
    public function findLast(
        string|object $text,
        string|object $search,
        int|object $start = null,
        int|object $end = null
    ): FunctionExpression {
        $arguments = [
            'text' => $text,
            'search' => $search,
        ];
        if (isset($start)) {
            $arguments['start'] = $start;
        }
        if (isset($end)) {
            $arguments['end'] = $end;
        }

        return new FunctionExpression('FIND_LAST', $arguments);
    }

    /**
     * Calculate the Damerau-Levenshtein distance between two strings.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#levenshtein_distance
     */
    public function levenshteinDistance(
        string|object $value1,
        string|object $value2
    ): FunctionExpression {
        return new FunctionExpression('LEVENSHTEIN_DISTANCE', [$value1, $value2]);
    }

    /**
     * Convert upper-case letters in value to their lower-case counterparts.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#lower
     */
    public function lower(
        string|object $value,
    ): FunctionExpression {
        return new FunctionExpression('LOWER', [$value]);
    }

    /**
     * Return the string value with whitespace (or supplied characters) stripped from the start.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#ltrim
     */
    public function ltrim(
        string|object $value,
        string|object $char = null
    ): FunctionExpression {
        $arguments = [
            'value' => $value,
            'char' => $char,
        ];

        return new FunctionExpression('LTRIM', $arguments);
    }

    /**
     * Check whether the string search is contained in the string text.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#contains
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function regexMatches(
        string|object $text,
        string|object $regex,
        bool|object $caseInsensitive = false
    ): FunctionExpression {
        return new FunctionExpression('REGEX_MATCHES', [$text, $regex, $caseInsensitive]);
    }

    /**
     * Replace the pattern search with the string replacement in the string text, using regular expression matching.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#regex_replace
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function regexReplace(
        string|object $text,
        string|object $regex,
        string|object $replacement,
        bool|object $caseInsensitive = false
    ): FunctionExpression {
        return new FunctionExpression('REGEX_REPLACE', [$text, $regex, $replacement, $caseInsensitive]);
    }

    /**
     * Split the given string text into a list of strings, using the separator.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#regex_split
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function regexSplit(
        string|object $text,
        string|object $splitExpression,
        bool|object $caseInsensitive = false,
        int|object $limit = null
    ): FunctionExpression {
        $arguments = [
            'text' => $text,
            'splitExpression' => $splitExpression,
            'caseInsensitive' => $caseInsensitive,
        ];
        if (isset($limit)) {
            $arguments['limit'] = $limit;
        }

        return new FunctionExpression('REGEX_SPLIT', $arguments);
    }

    /**
     * Check whether the pattern search is contained in the string text, using regular expression matching.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#regex_test
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function regexTest(
        string|object $text,
        string|object $search,
        bool|object $caseInsensitive = false
    ): FunctionExpression {
        return new FunctionExpression('REGEX_TEST', [$text, $search, $caseInsensitive]);
    }

    /**
     * Return the string value with whitespace (or supplied characters) stripped from the end.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#rtrim
     */
    public function rtrim(
        string|object $value,
        string|object $char = null
    ): FunctionExpression {
        $arguments = [
            'value' => $value,
            'char' => $char,
        ];

        return new FunctionExpression('RTRIM', $arguments);
    }

    /**
     * Return the soundex fingerprint of value.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#soundex
     */
    public function soundex(
        string|object $value,
    ): FunctionExpression {
        return new FunctionExpression('SOUNDEX', [$value]);
    }

    /**
     * Split the given string text into a list of strings, using the separator.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#regex_split
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function split(
        string|object $value,
        string|object $separator,
        int|object $limit = null
    ): FunctionExpression {
        $arguments = [
            'value' => $value,
            'separator' => $separator,
        ];
        if (isset($limit)) {
            $arguments['limit'] = $limit;
        }

        return new FunctionExpression('SPLIT', $arguments);
    }

    /**
     * Replace search values in the string value.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#substitute
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param  string|array<mixed>|object  $search
     * @param  string|array<mixed>|object  $replace
     */
    public function substitute(
        string|object $value,
        string|array|object $search,
        string|array|object $replace,
        int|object $limit = null
    ): FunctionExpression {
        $arguments = [
            'value' => $value,
            'search' => $search,
            'replace' => $replace,
        ];
        if (isset($limit)) {
            $arguments['limit'] = $limit;
        }

        return new FunctionExpression('SUBSTITUTE', $arguments);
    }

    /**
     * Return a substring of value.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#substring
     */
    public function substring(
        string|object $value,
        int|object $offset,
        int|object $length = null
    ): FunctionExpression {
        $arguments = [
            'value' => $value,
            'offset' => $offset,
        ];
        if (isset($length)) {
            $arguments['length'] = $length;
        }

        return new FunctionExpression('SUBSTRING', $arguments);
    }

    /**
     * Return a substring of value.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#tokens
     */
    public function tokens(
        string|object $input,
        string|object $analyzer
    ): FunctionExpression {
        return new FunctionExpression('TOKENS', [$input, $analyzer]);
    }

    /**
     * Return the string value with whitespace stripped from the start and/or end.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#trim
     */
    public function trim(
        string|object $value,
        string|int|object $type = null
    ): FunctionExpression {
        $arguments = [
            'value' => $value,
            'type' => $type,
        ];

        return new FunctionExpression('TRIM', $arguments);
    }

    /**
     * Convert lower-case letters in value to their upper-case counterparts.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#upper
     */
    public function upper(
        string|object $value,
    ): FunctionExpression {
        return new FunctionExpression('UPPER', [$value]);
    }

    /**
     * Return a universally unique identifier value.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-string.html#uuid
     *
     * @return FunctionExpression
     */
    public function uuid()
    {
        return new FunctionExpression('UUID');
    }
}
