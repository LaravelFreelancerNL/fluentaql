<?php

namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Clauses\EdgeCollectionsClause;
use LaravelFreelancerNL\FluentAQL\Clauses\GraphClause;
use LaravelFreelancerNL\FluentAQL\Clauses\PruneClause;
use LaravelFreelancerNL\FluentAQL\Clauses\TraverseClause;
use LaravelFreelancerNL\FluentAQL\Clauses\WithClause;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasGraphClauses
 * API calls to add clause commands to the builder.
 */
trait hasGraphClauses
{
    /**
     * Start a query with 'WITH' to prevent graph traversal deadlocks.
     * This is required in clusters.
     * @link https://www.arangodb.com/docs/stable/aql/operations-with.html
     *
     * @return QueryBuilder
     */
    public function with() : QueryBuilder
    {
        $collections = func_get_args();
        foreach ($collections as $key => $collection) {
            $this->registerCollections($collection, 'read');
            $collections[$key] = $this->normalizeArgument($collection, 'Collection');
        }

        $this->addCommand(new WithClause($collections));

        return $this;
    }

    /**
     * Traverse a graph
     * Must be preceded by a FOR clause.
     * @link https://www.arangodb.com/docs/stable/aql/graphs-traversals.html
     *
     * @param $fromVertex
     * @param string $inDirection
     * @param null $toVertex
     * @param bool $kShortestPaths
     * @return QueryBuilder
     */
    public function traverse($fromVertex, $inDirection = 'outbound', $toVertex = null, $kShortestPaths = false) : QueryBuilder
    {
        $fromVertex = $this->normalizeArgument($fromVertex, 'Id');
        $inDirection = $this->normalizeArgument($inDirection, 'Direction');

        if ($toVertex !== null) {
            $toVertex = $this->normalizeArgument($toVertex, 'Id');
        }

        $this->addCommand(new TraverseClause($fromVertex, $inDirection, $toVertex, $kShortestPaths));

        return $this;
    }

    /**
     * Shortest path alias for traverse.
     * @link arangodb.com/docs/stable/aql/graphs-shortest-path.html
     *
     * @param $fromVertex
     * @param string $inDirection
     * @param string $toVertex
     * @return QueryBuilder
     */
    public function shortestPath($fromVertex, $inDirection, $toVertex) : QueryBuilder
    {
        $this->traverse($fromVertex, $inDirection, $toVertex);

        return $this;
    }

    /**
     * K Shortest Paths alias for traverse.
     * @link https://www.arangodb.com/docs/stable/aql/graphs-kshortest-paths.html
     *
     * @param $fromVertex
     * @param string $inDirection
     * @param string $toVertex
     * @return QueryBuilder
     */
    public function kShortestPaths($fromVertex, $inDirection, $toVertex) : QueryBuilder
    {
        $this->traverse($fromVertex, $inDirection, $toVertex, true);

        return $this;
    }

    /**
     * Named Graph clause
     * Only usable after traverse/shortestPath/kShortestPaths clauses.
     * @link https://www.arangodb.com/docs/stable/aql/graphs-traversals.html
     *
     * @param string $graphName
     * @return QueryBuilder
     */
    public function graph(string $graphName) : QueryBuilder
    {
        $graphName = $this->normalizeArgument($graphName, 'Graph');

        $this->addCommand(new GraphClause($graphName));

        return $this;
    }

    /**
     * EdgeCollections Clause for unnamed graphs
     * Generates a list of edge collections to traverse through.
     * Only usable after traverse/shortestPath/kShortestPaths clauses.
     * @link https://www.arangodb.com/docs/stable/aql/graphs-traversals.html
     *
     * @param string|array $edgeCollection
     * @param string|null $direction
     * @return QueryBuilder
     */
    public function edgeCollections($edgeCollection) : QueryBuilder
    {
        $collections = [];

        //normalize string|null $edgeCollections and $direction
        if (is_string($edgeCollection)) {
            $collections[] = $this->normalizeEdgeCollections($edgeCollection);
        }

        if (is_array($edgeCollection)) {
            //Wandel door de array
            $collections = array_map(function ($expression) {
                return $this->normalizeEdgeCollections($expression);
            }, $edgeCollection);
        }

        $this->addCommand(new EdgeCollectionsClause($collections));

        return $this;
    }

    /**
     * Prune a graph traversal.
     * @link https://www.arangodb.com/docs/stable/aql/graphs-traversals.html#pruning
     *
     * @param string $attribute
     * @param string $comparisonOperator
     * @param mixed $value
     * @param string $logicalOperator
     * @return QueryBuilder
     */
    public function prune($attribute, $comparisonOperator = '==', $value = null, $logicalOperator = 'AND') : QueryBuilder
    {
        //create array of predicates if $leftOperand isn't an array already
        if (is_string($attribute)) {
            $attribute = [[$attribute, $comparisonOperator, $value,  $logicalOperator]];
        }

        $predicates = $this->normalizePredicates($attribute);

        $this->addCommand(new PruneClause($predicates));

        return $this;
    }
}
