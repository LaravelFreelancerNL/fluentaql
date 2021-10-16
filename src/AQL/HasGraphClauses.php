<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Clauses\EdgeCollectionsClause;
use LaravelFreelancerNL\FluentAQL\Clauses\GraphClause;
use LaravelFreelancerNL\FluentAQL\Clauses\PruneClause;
use LaravelFreelancerNL\FluentAQL\Clauses\TraverseClause;
use LaravelFreelancerNL\FluentAQL\Clauses\TraverseKPathsClause;
use LaravelFreelancerNL\FluentAQL\Clauses\TraverseKShortestPathsClause;
use LaravelFreelancerNL\FluentAQL\Clauses\TraverseShortestPathClause;
use LaravelFreelancerNL\FluentAQL\Clauses\WithClause;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasGraphClauses
 * API calls to add clause commands to the builder.
 */
trait HasGraphClauses
{

    abstract public function addCommand($command);

    /**
     * Start a query with 'WITH' to prevent graph traversal deadlocks.
     * This is required in clusters.
     *
     * @link https://www.arangodb.com/docs/stable/aql/operations-with.html
     *
     * @return QueryBuilder
     */
    public function with(): self
    {
        $this->addCommand(new WithClause(func_get_args()));

        return $this;
    }

    /**
     * Traverse a graph
     * Must be preceded by a FOR clause.
     *
     * @link https://www.arangodb.com/docs/stable/aql/graphs-traversals.html
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param $fromVertex
     * @param  string  $inDirection
     * @return QueryBuilder
     */
    public function traverse(
        $fromVertex,
        $inDirection = 'outbound'
    ): self {
        $this->addCommand(new TraverseClause($fromVertex, $inDirection));

        return $this;
    }

    /**
     * Shortest path alias for traverse.
     *
     * @link arangodb.com/docs/stable/aql/graphs-shortest-path.html
     *
     * @param $fromVertex
     * @param string $inDirection
     * @param string $toVertex
     *
     * @return QueryBuilder
     */
    public function shortestPath($fromVertex, $inDirection, $toVertex): self
    {
        $this->addCommand(new TraverseShortestPathClause($fromVertex, $inDirection, $toVertex));

        return $this;
    }

    /**
     * K Shortest Paths alias for traverse.
     *
     * @link https://www.arangodb.com/docs/stable/aql/graphs-kshortest-paths.html
     *
     * @param $fromVertex
     * @param string $inDirection
     * @param string $toVertex
     *
     * @return QueryBuilder
     */
    public function kShortestPaths($fromVertex, $inDirection, $toVertex): QueryBuilder
    {
        $this->addCommand(new TraverseKShortestPathsClause($fromVertex, $inDirection, $toVertex));

        return $this;
    }

    /**
     * K Paths alias for traverse.
     *
     * @link https://www.arangodb.com/docs/stable/aql/graphs-k-paths.html
     *
     * @param $fromVertex
     * @param string $inDirection
     * @param string $toVertex
     *
     * @return QueryBuilder
     */
    public function kPaths($fromVertex, $inDirection, $toVertex): QueryBuilder
    {
        $this->addCommand(new TraverseKPathsClause($fromVertex, $inDirection, $toVertex));

        return $this;
    }


    /**
     * Named Graph clause
     * Only usable after traverse/shortestPath/kShortestPaths Clauses.
     *
     * @link https://www.arangodb.com/docs/stable/aql/graphs-traversals.html
     *
     * @param string $graphName
     *
     * @return QueryBuilder
     */
    public function graph(string $graphName): QueryBuilder
    {
        $this->addCommand(new GraphClause($graphName));

        return $this;
    }

    /**
     * EdgeCollections Clause for unnamed graphs
     * Generates a list of edge collections to traverse through.
     * Only usable after traverse/shortestPath/kShortestPaths Clauses.
     *
     * @link https://www.arangodb.com/docs/stable/aql/graphs-traversals.html
     *
     * @param  array  $edgeCollections
     * @return QueryBuilder
     */
    public function edgeCollections(...$edgeCollections): QueryBuilder
    {
        $this->addCommand(new EdgeCollectionsClause($edgeCollections));

        return $this;
    }

    /**
     * Prune a graph traversal.
     *
     * @link https://www.arangodb.com/docs/stable/aql/graphs-traversals.html#pruning
     *
     * @param $leftOperand
     * @param  string  $comparisonOperator
     * @param  null  $rightOperand
     * @param  string  $logicalOperator
     *
     * @return QueryBuilder
     */
    public function prune(
        $leftOperand,
        $comparisonOperator = null,
        $rightOperand = null,
        $logicalOperator = null
    ): QueryBuilder {
        $predicates = $leftOperand;
        if (! is_array($predicates)) {
            $predicates = [[$leftOperand, $comparisonOperator, $rightOperand, $logicalOperator]];
        }

        $this->addCommand(new PruneClause($predicates));

        return $this;
    }
}
