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
     * 'for' clause syntax
     * @test
     */
    public function for_clause_syntax()
    {
        $result = AQB::for('u', 'users')->get();
        self::assertEquals('FOR u IN users', $result->query);

        $result = AQB::for(['v', 'e', 'p'], 'graph')->get();
        self::assertEquals('FOR v, e, p IN graph', $result->query);
    }

    /**
     * filter clause syntax
     * @test
     */
    function filter_clause_syntax()
    {
        $result = AQB::filter('u.active', '==', 'true')->get();
        self::assertEquals('FILTER @1_1 == @1_2', $result->query);

        $result = AQB::filter('u.active', '==', 'true', 'OR')->get();
        self::assertEquals('FILTER @1_1 == @1_2', $result->query);

        $result = AQB::filter('u.active', 'true')->get();
        self::assertEquals('FILTER @1_1 == @1_2', $result->query);

        $result = AQB::filter('u.active')->get();
        self::assertEquals('FILTER @1_1 == @1_2', $result->query);

        $result = AQB::filter([['u.active', '==', 'true'], ['u.age']])->get();
        self::assertEquals('FILTER @1_1 == @1_2 AND @1_3 == @1_4', $result->query);

        //FIXME: Embedded filters
//        $result = AQB::filter(
//            [
//                ['u.active', '==', 'true'], ['u.age'],
//                ['u.name', '==', 'Brandon'], ['u.surname', 'Stark'],
//            ]
//        )->get();
//        self::assertEquals('FILTER @1_1 == @1_2 AND @1_3 == @1_4', $result->query);
    }


    /**
     * sort clause syntax
     * @test
     */
    function sort_clause_syntax()
    {
        $result  = AQB::sort('u.name', 'DESC')->get();
        self::assertEquals('SORT u.name DESC', $result->query);

        $result  = AQB::sort('null')->get();
        self::assertEquals('SORT null', $result->query);

        $result  = AQB::sort()->get();
        self::assertEquals('SORT null', $result->query);

        $result  = AQB::sort(['u.name'])->get();
        self::assertEquals('SORT u.name', $result->query);

        $result  = AQB::sort(['u.name', 'u.age'])->get();
        self::assertEquals('SORT u.name, u.age', $result->query);

        $result  = AQB::sort([['u.age', 'DESC']])->get();
        self::assertEquals('SORT u.age DESC', $result->query);

        $result  = AQB::sort(['u.name', ['u.age', 'DESC']])->get();
        self::assertEquals('SORT u.name, u.age DESC', $result->query);

        $result  = AQB::sort(['u.name', 'DESC'])->get();
        self::assertNotEquals('SORT u.name DESC', $result->query);
    }

    /**
     * 'return' clause Syntax
     * @test
     */
    public function return_clause_syntax()
    {
        $result = AQB::return('u.name')->get();
        self::assertEquals('RETURN u.name', $result->query);

        $result = AQB::return("1 + 1")->get();
        self::assertEquals('RETURN 1 + 1', $result->query);

        $result = AQB::return("1 + 1", true)->get();
        self::assertEquals('RETURN DISTINCT 1 + 1', $result->query);
    }
}
