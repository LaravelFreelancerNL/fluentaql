<?php
namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Clauses\WithClause;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasGraphClauses
 * API calls to add clause commands to the builder.
 *
 * @package LaravelFreelancerNL\FluentAQL\API
 */
trait hasGraphClauses
{

    /**
     * Start a query with 'WITH' to prevent graph traversal deadlocks.
     * @link https://www.arangodb.com/docs/stable/aql/operations-with.html
     *
     * @return QueryBuilder
     */
    public function with() : QueryBuilder
    {
        $collections = func_get_args();
        foreach ($collections as $key => $collection) {
            $this->registerCollections($collection, 'read');
            $collections[$key] = $this->normalizeArgument($collection, 'collection');
        }

        $this->addCommand(new WithClause($collections));

        return $this;
    }

}
