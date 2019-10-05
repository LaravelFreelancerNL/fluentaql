<?php

use LaravelFreelancerNL\FluentAQL\Facades\AQB;

/**
 * Class StructureTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\API\hasQueryClauses.php
 */
class GraphClausesTest extends TestCase
{
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