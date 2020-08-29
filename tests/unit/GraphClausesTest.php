<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class StructureTest.
 *
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasQueryClauses.php
 */
class GraphClausesTest extends TestCase
{
    public function testWithClause()
    {
        $result = (new QueryBuilder())
            ->with('Characters', 'ChildOf', 'Locations', 'Traits')
            ->get();
        self::assertEquals('WITH Characters, ChildOf, Locations, Traits', $result->query);
    }

    public function testTraverseClause()
    {
        $result = (new QueryBuilder())
            ->traverse('Characters/BranStark', 'outbound')
            ->get();
        self::assertEquals('OUTBOUND "Characters/BranStark"', $result->query);
    }

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

    public function testKShortestPaths()
    {
        $result = (new QueryBuilder())
            ->kShortestPaths('Characters/BranStark', 'outbound', 'Characters/NedStark')
            ->get();
        self::assertEquals('OUTBOUND K_SHORTEST_PATHS "Characters/BranStark" TO "Characters/NedStark"', $result->query);
    }

    public function testGraphClause()
    {
        $result = (new QueryBuilder())
            ->graph('relations')
            ->get();
        self::assertEquals('GRAPH "relations"', $result->query);
    }

    public function testEdgeCollectionListClauseStringInput()
    {
        $result = (new QueryBuilder())
            ->edgeCollections('ChildOf')
            ->get();
        self::assertEquals('ChildOf', $result->query);
    }

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
            ->edgeCollections('ChildOf', ['KilledBy', 'ANY'])
            ->get();
        self::assertEquals('ChildOf, ANY KilledBy', $result->query);

        $result = (new QueryBuilder())
            ->edgeCollections('ChildOf', ['KilledBy', 'ANY'], 'SucceededBy')
            ->get();
        self::assertEquals('ChildOf, ANY KilledBy, SucceededBy', $result->query);
    }

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

    public function testPruneClauseWithMultiplePredicates()
    {
        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->prune([['u.active', '==', 'true'], ['u.age', '>', 18]])
             ->get();
        self::assertEquals('FOR u IN Users PRUNE u.active == true AND u.age > 18', $result->query);
    }
}
