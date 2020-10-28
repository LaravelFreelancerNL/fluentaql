<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;

/**
 * Geo AQL functions.
 *
 * @see https://www.arangodb.com/docs/stable/aql/functions-geo.html
 */
trait HasDocumentFunctions
{
    /**
     * Calculate the distance between two coordinates with the Haversine formula.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-document.html#merge
     *
     * @param  array  $documents
     * @return FunctionExpression
     */
    public function merge(...$documents)
    {
        return new FunctionExpression('MERGE', $documents);
    }
}
