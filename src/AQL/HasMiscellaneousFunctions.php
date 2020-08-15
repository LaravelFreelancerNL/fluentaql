<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\ListExpression;

/**
 * Trait hasFunctions.
 *
 * Miscellaneous AQL functions.
 *
 * @see https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html
 */
trait HasMiscellaneousFunctions
{
    /**
     * Return one or more specific documents from a collection.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html#document
     *
     * @param $collection
     * @param null|string|array|ListExpression $id
     *
     * @return FunctionExpression
     */
    public function document($collection, $id = null)
    {
        return new FunctionExpression('DOCUMENT', ['collection' => $collection, 'id' => $id]);
    }
}
