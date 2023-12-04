<?php

namespace Tests\Unit\AQL;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasQueryClauses
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasGraphClauses
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\ValidatesExpressions
 */
class GraphClausesTest extends TestCase
{
    /**
     * @covers \LaravelFreelancerNL\FluentAQL\Clauses\WithClause
     */
    public function testWithClause()
    {
        $result = (new QueryBuilder())
            ->with('Characters', 'ChildOf', 'Locations', 'Traits')
            ->get();
        self::assertEquals('WITH Characters, ChildOf, Locations, Traits', $result->query);
    }

    /**
     * @covers \LaravelFreelancerNL\FluentAQL\Clauses\TraverseClause
     */
    public function testTraverseClause()
    {
        $result = (new QueryBuilder())
            ->traverse('Characters/BranStark', 'outbound')
            ->get();
        self::assertEquals('OUTBOUND "Characters/BranStark"', $result->query);
    }

    /**
     * @covers \LaravelFreelancerNL\FluentAQL\Clauses\TraverseClause
     * @covers \LaravelFreelancerNL\FluentAQL\Clauses\TraverseShortestPathClause
     */
    public function testShortestPath()
    {
        $result = (new QueryBuilder())
            ->shortestPath('Characters/BranStark', 'outbound', 'Characters/NedStark')
            ->get();
        self::assertEquals('OUTBOUND SHORTEST_PATH "Characters/BranStark" TO "Characters/NedStark"', $result->query);

        $result = (new QueryBuilder())
            ->shortestPath('Characters/BranStark', 'outbound', 'Characters/NedStark')
            ->get();
        self::assertEquals('OUTBOUND SHORTEST_PATH "Characters/BranStark" TO "Characters/NedStark"', $result->query);
    }

    /**
     * @covers \LaravelFreelancerNL\FluentAQL\Clauses\TraverseClause
     * @covers \LaravelFreelancerNL\FluentAQL\Clauses\TraverseKShortestPathsClause
     */
    public function testKShortestPaths()
    {
        $result = (new QueryBuilder())
            ->kShortestPaths('Characters/BranStark', 'outbound', 'Characters/NedStark')
            ->get();
        self::assertEquals('OUTBOUND K_SHORTEST_PATHS "Characters/BranStark" TO "Characters/NedStark"', $result->query);
    }

    /**
     * @covers \LaravelFreelancerNL\FluentAQL\Clauses\TraverseClause
     * @covers \LaravelFreelancerNL\FluentAQL\Clauses\TraverseKPathsClause
     */
    public function testKPaths()
    {
        $result = (new QueryBuilder())
            ->kPaths('Characters/BranStark', 'outbound', 'Characters/NedStark')
            ->get();
        self::assertEquals('OUTBOUND K_PATHS "Characters/BranStark" TO "Characters/NedStark"', $result->query);
    }

    /**
     * @covers \LaravelFreelancerNL\FluentAQL\Clauses\GraphClause
     */
    public function testGraphClause()
    {
        $result = (new QueryBuilder())
            ->graph('relations')
            ->get();
        self::assertEquals('GRAPH "relations"', $result->query);
    }

    /**
     * @covers \LaravelFreelancerNL\FluentAQL\Clauses\EdgeCollectionsClause
     */
    public function testEdgeCollectionListClauseStringInput()
    {
        $result = (new QueryBuilder())
            ->edgeCollections('ChildOf')
            ->get();
        self::assertEquals('ChildOf', $result->query);
    }

    /**
     * @covers \LaravelFreelancerNL\FluentAQL\Clauses\EdgeCollectionsClause
     */
    public function testEdgeCollectionListClauseArrayInput()
    {
        $result = (new QueryBuilder())
            ->edgeCollections('ChildOf')
            ->get();
        self::assertEquals('ChildOf', $result->query);

        $result = (new QueryBuilder())
            ->edgeCollections('ChildOf', 'KilledBy')
            ->get();
        self::assertEquals('ChildOf, KilledBy', $result->query);

        $result = (new QueryBuilder())
            ->edgeCollections('ChildOf', 'ANY', 'KilledBy')
            ->get();
        self::assertEquals('ChildOf, ANY KilledBy', $result->query);

        $result = (new QueryBuilder())
            ->edgeCollections('ChildOf', 'ANY', 'KilledBy', 'SucceededBy')
            ->get();
        self::assertEquals('ChildOf, ANY KilledBy, SucceededBy', $result->query);
    }

    /**
     * @covers \LaravelFreelancerNL\FluentAQL\Clauses\PruneClause
     */
    public function testPruneClause()
    {
        $qb = (new QueryBuilder());
        $qb->for(['v', 'e', 'p'], '1..5')
            ->traverse('circles/A', 'OUTBOUND')
            ->graph('traversalGraph')
            ->prune('e.theTruth', '==', true)
            ->return([
                'vertices' => 'p.vertices[*]._key',
                'edges' => 'p.edges[*].label',
            ])
            ->get();
        self::assertEquals(
            'FOR v, e, p IN 1..5 OUTBOUND "circles/A" GRAPH "traversalGraph"'
            . ' PRUNE e.theTruth == true'
            . ' RETURN {"vertices":p.vertices[*]._key,"edges":p.edges[*].label}',
            $qb->query
        );

        $qb = (new QueryBuilder());
        $qb->for(['v', 'e', 'p'], '1..5')
            ->traverse('circles/A', 'OUTBOUND')
            ->graph('traversalGraph')
            ->prune('e.theTruth', '==', true, 'OR')
            ->return([
                'vertices' => 'p.vertices[*]._key',
                'edges' => 'p.edges[*].label',
            ])
            ->get();
        self::assertEquals(
            'FOR v, e, p IN 1..5 OUTBOUND "circles/A" GRAPH "traversalGraph"'
            . ' PRUNE e.theTruth == true'
            . ' RETURN {"vertices":p.vertices[*]._key,"edges":p.edges[*].label}',
            $qb->query
        );
    }

    /**
     * @covers \LaravelFreelancerNL\FluentAQL\Clauses\PruneClause
     */
    public function testPruneClauseWithMultiplePredicates()
    {
        $qb = (new QueryBuilder());
        $qb->for(['v', 'e', 'p'], '1..5')
            ->traverse('circles/A', 'OUTBOUND')
            ->graph('traversalGraph')
            ->prune([['e.active', '==', 'true'], ['e.age', '>', 18]])
            ->return([
                'vertices' => 'p.vertices[*]._key',
                'edges' => 'p.edges[*].label',
            ])
            ->get();
        self::assertEquals(
            'FOR v, e, p IN 1..5 OUTBOUND "circles/A" GRAPH "traversalGraph"'
            . ' PRUNE e.active == true AND e.age > 18'
            . ' RETURN {"vertices":p.vertices[*]._key,"edges":p.edges[*].label}',
            $qb->query
        );
    }

    /**
     * @covers \LaravelFreelancerNL\FluentAQL\Clauses\PruneClause
     */
    public function testPruneClauseWithVariable()
    {
        $qb = (new QueryBuilder());
        $qb->for(['v', 'e', 'p'], '1..5')
            ->traverse('circles/A', 'OUTBOUND')
            ->graph('traversalGraph')
            ->prune(
                'e.theTruth',
                '==',
                true,
                'OR',
                'pruneCondition'
            )
            ->return([
                'vertices' => 'p.vertices[*]._key',
                'edges' => 'p.edges[*].label',
            ])
            ->get();
        self::assertEquals(
            'FOR v, e, p IN 1..5 OUTBOUND "circles/A" GRAPH "traversalGraph"'
            . ' PRUNE pruneCondition = e.theTruth == true'
            . ' RETURN {"vertices":p.vertices[*]._key,"edges":p.edges[*].label}',
            $qb->query
        );

        $qb = (new QueryBuilder());
        $qb->for(['v', 'e', 'p'], '1..5')
            ->traverse('circles/A', 'OUTBOUND')
            ->graph('traversalGraph')
            ->prune(
                [
                    'e.theTruth',
                    '==',
                    true,
                ],
                pruneVariable: 'pruneCondition'
            )
            ->return([
                'vertices' => 'p.vertices[*]._key',
                'edges' => 'p.edges[*].label',
            ])
            ->get();
        self::assertEquals(
            'FOR v, e, p IN 1..5 OUTBOUND "circles/A" GRAPH "traversalGraph"'
            . ' PRUNE pruneCondition = e.theTruth == true'
            . ' RETURN {"vertices":p.vertices[*]._key,"edges":p.edges[*].label}',
            $qb->query
        );

        $qb = (new QueryBuilder());
        $qb->for(['v', 'e', 'p'], '1..5')
            ->traverse('circles/A', 'OUTBOUND')
            ->graph('traversalGraph')
            ->prune(
                [
                    [
                        'e.theTruth', '==', true,
                    ],
                    [
                        'e.theTruth', '!=', false,
                    ],
                ],
                pruneVariable: 'pruneCondition'
            )
            ->return([
                'vertices' => 'p.vertices[*]._key',
                'edges' => 'p.edges[*].label',
            ])
            ->get();
        self::assertEquals(
            'FOR v, e, p IN 1..5 OUTBOUND "circles/A" GRAPH "traversalGraph"'
            . ' PRUNE pruneCondition = e.theTruth == true AND e.theTruth != false'
            . ' RETURN {"vertices":p.vertices[*]._key,"edges":p.edges[*].label}',
            $qb->query
        );
    }
}
