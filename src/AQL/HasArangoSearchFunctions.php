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

        /** @var string $analyzer */
        $analyzer = array_pop($arguments);

        $predicates = $arguments;
        if (!is_array($predicates[0])) {
            $predicates = [[
                ...$predicates,
            ]];
        }
        $preppedArguments = [
            'predicates' => $predicates,
            'analyzer' => $analyzer,
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

        /** @var int|float $boost */
        $boost = array_pop($arguments);

        $predicates = $arguments;
        if (!is_array($predicates[0])) {
            $predicates = [[
                ...$predicates,
            ]];
        }
        $preppedArguments = [
            'predicates' => $predicates,
            'boost' => $boost,
        ];

        return new FunctionExpression('BOOST', $preppedArguments);
    }

    /**
     * Sorts documents using the Best Matching 25 algorithm (Okapi BM25).
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#bm25
     */
    public function bm25(
        string|Expression $doc,
        int|float|Expression|QueryBuilder $k = null,
        int|float|Expression|QueryBuilder $b = null
    ): FunctionExpression {
        $arguments = [
            $doc,
            $k,
            $b,
        ];
        $arguments = array_filter($arguments);

        return new FunctionExpression('BM25', $arguments);
    }

    /**
     * Sorts documents using the term frequency–inverse document frequency algorithm (TF-IDF).
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#tfidf
     */
    public function tfidf(
        string|Expression $doc,
        bool|Expression|QueryBuilder $normalize = null
    ): FunctionExpression {
        $arguments = [
            $doc,
        ];
        if (isset($normalize)) {
            $arguments[] = $normalize;
        }

        return new FunctionExpression('TFIDF', $arguments);
    }

    /**
     * Match documents where the attribute at path is present and is of the specified data type.
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#exists
     */
    public function exists(
        Expression|QueryBuilder|string $path,
        Expression|string|QueryBuilder $type = null
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
     */
    public function inRange(
        string|Expression $path,
        int|float|string|Expression|QueryBuilder $low,
        int|float|string|Expression|QueryBuilder $high,
        bool|Expression|QueryBuilder $includeLow = null,
        bool|Expression|QueryBuilder $includeHigh = null
    ): FunctionExpression {
        $arguments = [
            'path' => $path,
            'low' => $low,
            'high' => $high,
            'includeLow' => $includeLow,
            'includeHigh' => $includeHigh,
        ];
        $arguments = $this->unsetNullValues($arguments);

        return new FunctionExpression('IN_RANGE', $arguments);
    }

    /**
     * Match documents with a Damerau-Levenshtein distance lower than or equal to
     * the distance between the stored attribute value and target.
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#levenshtein_match
     *
     * @param  string|object  $path
     * @param  string|object  $target
     * @param  int|object  $distance
     * @param  null|bool|object  $transpositions
     * @param  null|int|object  $maxTerms
     * @param  null|string|object  $prefix
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
            'path' => $path,
            'target' => $target,
            'distance' => $distance,
            'transpositions' => $transpositions,
            'maxTerms' => $maxTerms,
            'prefix' => $prefix,
        ];
        $arguments = $this->unsetNullValues($arguments);

        return new FunctionExpression('LEVENSHTEIN_MATCH', $arguments);
    }

    /**
     * Check whether the pattern search is contained in the attribute denoted by path, using wildcard matching.
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#like
     */
    public function like(
        string|object $path,
        mixed $search
    ): FunctionExpression {
        $arguments = [
            'path' => $path,
            'search' => $search,
        ];

        return new FunctionExpression('LIKE', $arguments);
    }

    /**
     * Match documents with a Damerau-Levenshtein distance lower than or equal to
     * the distance between the stored attribute value and target.
     *
     * https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#levenshtein_match
     *
     * @param  string|object  $path
     * @param  string|object  $target
     * @param  null|int|float|object  $threshold
     * @param  null|string|object  $analyzer
     */
    public function nGramMatch(
        mixed $path,
        mixed $target,
        mixed $threshold = null,
        mixed $analyzer = null
    ): FunctionExpression {
        $arguments = [
            'path' => $path,
            'target' => $target,
            'threshold' => $threshold,
            'analyzer' => $analyzer,
        ];
        $arguments = $this->unsetNullValues($arguments);

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
