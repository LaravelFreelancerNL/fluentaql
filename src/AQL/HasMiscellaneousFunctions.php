<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\ListExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

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
     * @param string|array<mixed>|QueryBuilder|Expression $collection
     * @param string|array<mixed>|QueryBuilder|Expression|null $id
     */
    public function document(
        string|array|QueryBuilder|Expression $collection,
        string|array|QueryBuilder|Expression $id = null
    ): FunctionExpression {
        return new FunctionExpression('DOCUMENT', ['collection' => $collection, 'id' => $id]);
    }

    /**
     * Return the first alternative that is a document, and null if none of the alternatives is a document.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html#first_document
     *
     * @param mixed $arguments
     *
     * @return FunctionExpression
     */
    public function firstDocument(...$arguments)
    {
        return new FunctionExpression('FIRST_DOCUMENT', $arguments);
    }
}
