<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Geo AQL functions.
 *
 * @see https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html
 */
trait HasArangoSearchFunctions
{
    /**
     * set the analyzer for the given search expression
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#analyzer
     */
    public function analyzer(): FunctionExpression
    {
        $arguments = func_get_args();

        $analyzer = array_pop($arguments);

        $predicates = $arguments;
        if (! is_array($predicates[0])) {
            $predicates = [[
                ...$predicates
            ]];
        }
        $preppedArguments = [
            'predicates' => $predicates,
            'analyzer' => $analyzer
        ];
        return new FunctionExpression('ANALYZER', $preppedArguments);
    }

    /**
     * Override the boost value of the search expression
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#boost
     */
    public function boost(): FunctionExpression
    {
        $arguments = func_get_args();

        $boost = array_pop($arguments);

        $predicates = $arguments;
        if (! is_array($predicates[0])) {
            $predicates = [[
                ...$predicates
            ]];
        }
        $preppedArguments = [
            'predicates' => $predicates,
            'boost' => $boost
        ];
        return new FunctionExpression('BOOST', $preppedArguments);
    }

    /**
     * Sorts documents using the Best Matching 25 algorithm (Okapi BM25).
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#bm25
     */
    public function bm25(mixed $doc, mixed $k = null, mixed $b = null): FunctionExpression
    {
        $arguments = [
            $doc,
            $k,
            $b
        ];
        $arguments = array_filter($arguments);

        return new FunctionExpression('BM25', $arguments);
    }

    /**
     * Sorts documents using the term frequency–inverse document frequency algorithm (TF-IDF).
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#tfidf
     */
    public function tfidf(mixed $doc, mixed $normalize = null): FunctionExpression
    {
        $arguments = [
            $doc,
            $normalize,
        ];
        $arguments = array_filter($arguments);

        return new FunctionExpression('TFIDF', $arguments);
    }

    /**
     * Match documents where the attribute at path is present and is of the specified data type.
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#exists
     *
     * @param string|QueryBuilder|Expression $path
     * @param QueryBuilder|Expression|string|null $type
     * @return FunctionExpression
     */
    public function exists(
        Expression|QueryBuilder|string $path,
        Expression|null|string|QueryBuilder $type = null
    ): FunctionExpression {
        $arguments = [
            $path,
            $type,
        ];
        $arguments = array_filter($arguments);

        return new FunctionExpression('EXISTS', $arguments);
    }

    /**
     * Match documents where the attribute at path is present and is of the specified data type.
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#in_range
     *
     * @param string|QueryBuilder|Expression $path
     * @param mixed $low
     * @param mixed $high
     * @param mixed|null $includeLow
     * @param mixed|null $includeHigh
     * @return FunctionExpression
     */
    public function inRange(
        mixed $path,
        mixed $low,
        mixed $high,
        mixed $includeLow = null,
        mixed $includeHigh = null
    ): FunctionExpression {
        $arguments = [
            "path" => $path,
            "low" => $low,
            "high" => $high,
            "includeLow" => $includeLow,
            "includeHigh" => $includeHigh
        ];
        $arguments = array_filter(
            $arguments,
            function ($value) {
                return ! is_null($value);
            }
        );

        return new FunctionExpression('IN_RANGE', $arguments);
    }

    /**
     * Match documents with a Damerau-Levenshtein distance lower than or equal to
     * the distance between the stored attribute value and target.
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#levenshtein_match
     *
     * @param string|QueryBuilder|Expression $path
     * @param string|QueryBuilder|Expression $target
     * @param int|QueryBuilder|Expression $distance
     * @param null|bool|QueryBuilder|Expression $transpositions
     * @param null|int|QueryBuilder|Expression $maxTerms
     * @param null|string|QueryBuilder|Expression $prefix
     * @return FunctionExpression
     */
    public function levenshteinMatch(
        mixed $path,
        mixed $target,
        mixed $distance,
        mixed $transpositions = null,
        mixed $maxTerms = null,
        mixed $prefix = null
    ): FunctionExpression {
        $arguments = [
            "path" => $path,
            "target" => $target,
            "distance" => $distance,
            "transpositions" => $transpositions,
            "maxTerms" => $maxTerms,
            "prefix" => $prefix,
        ];
        $arguments = array_filter(
            $arguments,
            function ($value) {
                return ! is_null($value);
            }
        );

        return new FunctionExpression('LEVENSHTEIN_MATCH', $arguments);
    }

    /**
     * Check whether the pattern search is contained in the attribute denoted by path, using wildcard matching.
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#like
     *
     * @param string|QueryBuilder|Expression $path
     * @param string|QueryBuilder|Expression $target
     * @return FunctionExpression
     */
    public function like(
        mixed $path,
        mixed $search
    ): FunctionExpression {
        $arguments = [
            "path" => $path,
            "search" => $search,
        ];

        return new FunctionExpression('LIKE', $arguments);
    }

    /**
     * Match documents with a Damerau-Levenshtein distance lower than or equal to
     * the distance between the stored attribute value and target.
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#levenshtein_match
     *
     * @param string|QueryBuilder|Expression $path
     * @param string|QueryBuilder|Expression $target
     * @param null|int|float|QueryBuilder|Expression $threshold
     * @param null|string|QueryBuilder|Expression $analyzer
     * @return FunctionExpression
     */
    public function ngramMatch(
        mixed $path,
        mixed $target,
        mixed $threshold = null,
        mixed $analyzer = null
    ): FunctionExpression {
        $arguments = [
            "path" => $path,
            "target" => $target,
            "threshold" => $threshold,
            "analyzer" => $analyzer
        ];
        $arguments = array_filter(
            $arguments,
            function ($value) {
                return ! is_null($value);
            }
        );

        return new FunctionExpression('NGRAM_MATCH', $arguments);
    }

    /**
     * Search for a phrase in the referenced attribute.
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#phrase
     */
    public function phrase(): FunctionExpression
    {
        $arguments = func_get_args();

        return new FunctionExpression('PHRASE', $arguments);
    }
}
