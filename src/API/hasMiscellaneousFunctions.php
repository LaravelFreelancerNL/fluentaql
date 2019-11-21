<?php

namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\ListExpression;

/**
 * Trait hasFunctions.
 *
 * Miscellaneous AQL functions.
 * @see https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html
 */
trait hasMiscellaneousFunctions
{
    /**
     * Return one or more specific documents from a collection.
     * @link https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html#document
     *
     * @param $collection
     * @param null|string|array|ListExpression $id
     * @return FunctionExpression
     */
    public function document($collection, $id = null)
    {
        $arguments = [];

        if ($id === null) {
            $id = $collection;
            $collection = null;
        }

        if ($collection !== null) {
            $arguments['collection'] = $this->normalizeArgument($collection, ['Collection', 'Id']);
        }
        $arguments['id'] = $this->normalizeArgument($id, ['Id', 'Key', 'Query', 'List']);

        return new FunctionExpression('DOCUMENT', $arguments);
    }
}
