<?php

use LaravelFreelancerNL\FluentAQL\Facades\AQB;

/**
 * Class StructureTest.
 *
 * @covers \LaravelFreelancerNL\FluentAQL\API\hasQueryClauses.php
 */
class GraphClausesTest extends TestCase
{
    /**
     * 'with' statement syntax.
     * @test
     */
    public function _with_statement_syntax()
    {
        $result = AQB::with('Characters', 'ChildOf', 'Locations', 'Traits')->get();
        self::assertEquals('WITH Characters, ChildOf, Locations, Traits', $result->query);
    }

    /**
     * traverse clause.
     * @test
     */
    public function traverse_clause()
    {
        $result = AQB::traverse('Characters/BranStark', 'outbound')->get();
        self::assertEquals('OUTBOUND "Characters/BranStark"', $result->query);
    }

    /**
     * shortest_path clause.
     * @test
     */
    public function shortest_path()
    {
        $result = AQB::traverse('Characters/BranStark', 'outbound', 'Characters/NedStark')->get();
        self::assertEquals('OUTBOUND SHORTEST_PATH "Characters/BranStark" TO "Characters/NedStark"', $result->query);

        $result = AQB::shortestPath('Characters/BranStark', 'outbound', 'Characters/NedStark')->get();
        self::assertEquals('OUTBOUND SHORTEST_PATH "Characters/BranStark" TO "Characters/NedStark"', $result->query);
    }

    /**
     * k shortest path clause.
     * @test
     */
    public function k_shortest_path()
    {
        $result = AQB::traverse('Characters/BranStark', 'outbound', 'Characters/NedStark', true)->get();
        self::assertEquals('OUTBOUND K_SHORTEST_PATHS "Characters/BranStark" TO "Characters/NedStark"', $result->query);

        $result = AQB::kShortestPaths('Characters/BranStark', 'outbound', 'Characters/NedStark')->get();
        self::assertEquals('OUTBOUND K_SHORTEST_PATHS "Characters/BranStark" TO "Characters/NedStark"', $result->query);
    }

    /**
     * graph clause.
     * @test
     */
    public function graph_clause()
    {
        $result = AQB::graph('relations')->get();
        self::assertEquals('GRAPH "relations"', $result->query);
    }

    /**
     * Edge Collection list clause.
     * @test
     */
    public function edge_collection_list_clause()
    {
        $result = AQB::edgeCollections('ChildOf')->get();
        self::assertEquals('ChildOf', $result->query);

        $result = AQB::edgeCollections(['ChildOf'])->get();
        self::assertEquals('ChildOf', $result->query);

        $result = AQB::edgeCollections(['ChildOf', 'KilledBy'])->get();
        self::assertEquals('ChildOf, KilledBy', $result->query);

        $result = AQB::edgeCollections(['ChildOf', ['KilledBy', 'ANY']])->get();
        self::assertEquals('ChildOf, ANY KilledBy', $result->query);

        $result = AQB::edgeCollections(['ChildOf', ['KilledBy', 'ANY'], 'SucceededBy'])->get();
        self::assertEquals('ChildOf, ANY KilledBy, SucceededBy', $result->query);
    }

    /**
     * Prune clause syntax.
     * @test
     */
    public function prune_clause_syntax()
    {
        $result = AQB::for('u', 'Users')->prune('u.active', '==', 'true')->get();
        self::assertEquals('FOR u IN Users PRUNE u.active == true', $result->query);

        $result = AQB::for('u', 'Users')->prune('u.active', '==', 'true', 'OR')->get();
        self::assertEquals('FOR u IN Users PRUNE u.active == true', $result->query);

        $result = AQB::for('u', 'Users')->prune('u.active', 'true')->get();
        self::assertEquals('FOR u IN Users PRUNE u.active == true', $result->query);

        $result = AQB::for('u', 'Users')->prune('u.active')->get();
        self::assertEquals('FOR u IN Users PRUNE u.active == null', $result->query);

        $result = AQB::for('u', 'Users')->prune([['u.active', '==', 'true'], ['u.age']])->get();
        self::assertEquals('FOR u IN Users PRUNE u.active == true AND u.age == null', $result->query);
    }
}
