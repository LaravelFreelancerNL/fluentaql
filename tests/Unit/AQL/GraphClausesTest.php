<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class StructureTest.
 *
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasQueryClauses
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
     */
    public function testKShortestPaths()
    {
        $result = (new QueryBuilder())
            ->kShortestPaths('Characters/BranStark', 'outbound', 'Characters/NedStark')
            ->get();
        self::assertEquals('OUTBOUND K_SHORTEST_PATHS "Characters/BranStark" TO "Characters/NedStark"', $result->query);
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
        $result = (new QueryBuilder())
            ->for('u', 'users')
            ->prune('u.active', '==', 'true')
            ->get();
        self::assertEquals('FOR u IN users PRUNE u.active == true', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->prune('u.active', '==', 'true', 'OR')
            ->get();
        self::assertEquals('FOR u IN Users PRUNE u.active == true', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->prune('u.active', '==', 'true')
            ->get();
        self::assertEquals('FOR u IN Users PRUNE u.active == true', $result->query);
    }

    /**
     * @covers \LaravelFreelancerNL\FluentAQL\Clauses\PruneClause
     */
    public function testPruneClauseWithMultiplePredicates()
    {
        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->prune([['u.active', '==', 'true'], ['u.age', '>', 18]])
             ->get();
        self::assertEquals('FOR u IN Users PRUNE u.active == true AND u.age > 18', $result->query);
    }
}
