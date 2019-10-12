<?php
namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\KeyExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\ListExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\LiteralExpression;

/**
 * Trait hasFunctions
 *
 * Miscellaneous AQL functions.
 * @see https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html
 * @package LaravelFreelancerNL\FluentAQL\API
 */
trait hasMiscellaneousFunctions
{

    /**
     * Return one or more specific documents from a collection.
     * @link https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html#document
     *
     * @param $collection
     * @param null|string|array|KeyExpression|ListExpression $id
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
            $arguments['collection'] = $this->normalizeArgument($collection, ['collection', 'id']);
        }
        $arguments['id'] = $this->normalizeArgument($id, ['query', 'list', 'key', 'id']);

        return new FunctionExpression('DOCUMENT', $arguments);
    }
}
