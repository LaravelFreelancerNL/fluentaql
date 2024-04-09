<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
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
     * Throws an error if the given predicate fails.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html#assert--warn
     */
    public function assert(): FunctionExpression
    {
        $arguments = func_get_args();

        /** @var string $errorMessage */
        $errorMessage = array_pop($arguments);

        $predicates = $arguments;
        if (!is_array($predicates[0])) {
            $predicates = [[
                ...$predicates,
            ]];
        }
        $preppedArguments = [
            'predicates' => $predicates,
            'errorMessage' => $errorMessage,
        ];

        return new FunctionExpression('ASSERT', $preppedArguments);
    }

    /**
     * Return one or more specific documents from a collection.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html#document
     *
     * @param  string|array<mixed>|QueryBuilder|Expression  $collection
     * @param  string|array<mixed>|QueryBuilder|Expression|null  $id
     */
    public function document(
        string|array|QueryBuilder|Expression $collection,
        string|array|QueryBuilder|Expression $id = null
    ): FunctionExpression {
        return new FunctionExpression('DOCUMENT', ['collection' => $collection, 'id' => $id]);
    }

    /**
     * Returns the name of the current database.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html#current_database
     *
     * @return FunctionExpression
     */
    public function currentDatabase()
    {
        return new FunctionExpression('CURRENT_DATABASE');
    }

    /**
     * Return the name of the current user.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html#current_user
     *
     * @return FunctionExpression
     */
    public function currentUser()
    {
        return new FunctionExpression('CURRENT_USER');
    }

    /**
     * Return the first alternative that is a document, and null if none of the alternatives is a document.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html#first_document
     *
     * @param  mixed  $arguments
     * @return FunctionExpression
     */
    public function firstDocument(...$arguments)
    {
        return new FunctionExpression('FIRST_DOCUMENT', $arguments);
    }

    /**
     * Returns false and gives a warning if the given predicate fails.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html#assert--warn
     */
    public function warn(): FunctionExpression
    {
        $arguments = func_get_args();

        /** @var string $errorMessage */
        $errorMessage = array_pop($arguments);

        $predicates = $arguments;
        if (!is_array($predicates[0])) {
            $predicates = [[
                ...$predicates,
            ]];
        }
        $preppedArguments = [
            'predicates' => $predicates,
            'errorMessage' => $errorMessage,
        ];

        return new FunctionExpression('WARN', $preppedArguments);
    }
}
