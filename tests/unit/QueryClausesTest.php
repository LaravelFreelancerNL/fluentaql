<?php

use LaravelFreelancerNL\FluentAQL\Facades\AQB;

/**
 * Class StructureTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\API\hasQueryClauses.php
 */
class QueryClausesTest extends TestCase
{
    /**
     * raw AQL
     * @test
     */
    public function raw_aql()
    {
        $result = AQB::raw('FOR u IN Users FILTER u.email="test@test.com"')->get();
        self::assertEquals('FOR u IN Users FILTER u.email="test@test.com"', $result->query);

        //Todo: test bindings & collections
    }

    /**
     * 'for' statement syntax
     * @test
     */
    public function for_statement_syntax()
    {
        $result = AQB::for('u', 'users')->get();
        self::assertEquals('FOR u IN users', $result->query);

        $result = AQB::for(['v', 'e', 'p'], 'graph')->get();
        self::assertEquals('FOR v, e, p IN graph', $result->query);
    }

    /**
     * 'return' statement Syntax
     * @test
     */
    public function return_statement_syntax()
    {
        $result = AQB::return('u.name')->get();
        self::assertEquals('RETURN u.name', $result->query);

        $result = AQB::return("1 + 1")->get();
        self::assertEquals('RETURN 1 + 1', $result->query);

        $result = AQB::return("1 + 1", true)->get();
        self::assertEquals('RETURN DISTINCT 1 + 1', $result->query);
    }

    /**
     * 'with' statement syntax
     * @test
     */
    public function _with_statement_syntax()
    {
        $result = AQB::with('Characters', 'ChildOf', 'Locations', 'Traits')->get();
        self::assertEquals('WITH Characters, ChildOf, Locations, Traits', $result->query);
    }
}
