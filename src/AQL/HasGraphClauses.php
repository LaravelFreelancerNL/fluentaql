<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Clauses\EdgeCollectionsClause;
use LaravelFreelancerNL\FluentAQL\Clauses\GraphClause;
use LaravelFreelancerNL\FluentAQL\Clauses\PruneClause;
use LaravelFreelancerNL\FluentAQL\Clauses\TraverseClause;
use LaravelFreelancerNL\FluentAQL\Clauses\TraverseKPathsClause;
use LaravelFreelancerNL\FluentAQL\Clauses\TraverseKShortestPathsClause;
use LaravelFreelancerNL\FluentAQL\Clauses\TraverseShortestPathClause;
use LaravelFreelancerNL\FluentAQL\Clauses\WithClause;
use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
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
     */
    public function with(): self
    {
        /** @var array<array-key, Expression|string> $arguments */
        $arguments = func_get_args();
        $this->addCommand(new WithClause($arguments));

        return $this;
    }

    /**
     * Traverse a graph
     * Must be preceded by a FOR clause.
     *
     * @link https://www.arangodb.com/docs/stable/aql/graphs-traversals.html
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function traverse(
        string|QueryBuilder|Expression $fromVertex,
        string|QueryBuilder|Expression $inDirection = 'outbound'
    ): self {
        $this->addCommand(new TraverseClause($fromVertex, $inDirection));

        return $this;
    }

    /**
     * Shortest path alias for traverse.
     *
     * @link arangodb.com/docs/stable/aql/graphs-shortest-path.html
     */
    public function shortestPath(
        string|QueryBuilder|Expression $fromVertex,
        string|QueryBuilder|Expression $inDirection,
        string|QueryBuilder|Expression $toVertex
    ): self {
        $this->addCommand(new TraverseShortestPathClause($fromVertex, $inDirection, $toVertex));

        return $this;
    }

    /**
     * K Shortest Paths alias for traverse.
     *
     * @link https://www.arangodb.com/docs/stable/aql/graphs-kshortest-paths.html
     */
    public function kShortestPaths(
        string|QueryBuilder|Expression $fromVertex,
        string|QueryBuilder|Expression $inDirection,
        string|QueryBuilder|Expression $toVertex
    ): self {
        $this->addCommand(new TraverseKShortestPathsClause($fromVertex, $inDirection, $toVertex));

        return $this;
    }

    /**
     * K Paths alias for traverse.
     *
     * @link https://www.arangodb.com/docs/stable/aql/graphs-k-paths.html
     */
    public function kPaths(
        string|QueryBuilder|Expression $fromVertex,
        string|QueryBuilder|Expression $inDirection,
        string|QueryBuilder|Expression $toVertex
    ): self {
        $this->addCommand(new TraverseKPathsClause($fromVertex, $inDirection, $toVertex));

        return $this;
    }

    /**
     * Named Graph clause
     * Only usable after traverse/shortestPath/kShortestPaths Clauses.
     *
     * @link https://www.arangodb.com/docs/stable/aql/graphs-traversals.html
     */
    public function graph(
        string|QueryBuilder|Expression $graphName
    ): self {
        $this->addCommand(new GraphClause($graphName));

        return $this;
    }

    /**
     * EdgeCollections Clause for unnamed graphs
     * Generates a list of edge collections to traverse through.
     * Only usable after traverse/shortestPath/kShortestPaths Clauses.
     *
     * @link https://www.arangodb.com/docs/stable/aql/graphs-traversals.html
     */
    public function edgeCollections(): self
    {
        /** @var array<array<string>|Expression> $edgeCollections */
        $edgeCollections = func_get_args();

        $this->addCommand(new EdgeCollectionsClause($edgeCollections));

        return $this;
    }

    /**
     * Prune a graph traversal.
     *
     * @link https://www.arangodb.com/docs/stable/aql/graphs-traversals.html#pruning
     *
     * @param object|array<mixed>|string|int|float|bool|null $leftOperand
     * @param object|array<mixed>|string|int|float|bool|null $rightOperand
     */
    public function prune(
        object|array|string|int|float|bool|null $leftOperand,
        string|QueryBuilder|Expression $comparisonOperator = null,
        object|array|string|int|float|bool|null $rightOperand = null,
        string|QueryBuilder|Expression $logicalOperator = null,
        string $pruneVariable = null
    ): self {
        $predicates = $leftOperand;
        if (! is_array($predicates)) {
            $predicates = [[$leftOperand, $comparisonOperator, $rightOperand, $logicalOperator]];
        }

        $this->addCommand(new PruneClause($predicates, $pruneVariable));

        return $this;
    }
}
