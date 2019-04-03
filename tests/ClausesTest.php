<?php

use LaravelFreelancerNL\FluentAQL\Facades\AQB;

/**
 * Class StructureTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses
 */
class ClausesTest extends TestCase
{
    /**
     * raw AQL
     * @test
     */
    function raw_aql()
    {
        $raw = AQB::raw('FOR u IN Users FILTER u.email="test@test.com"')->get();


        self::assertEquals('FOR u IN Users FILTER u.email="test@test.com"', $raw->query);
    }


    /**
     * 'for' statement syntax
     * @test
     */
    function for_statement_syntax()
    {
        $result = AQB::for('u')->get();
        self::assertEquals('FOR u', $result->query);

        $result = AQB::for('u')->get();
        self::assertEquals('FOR u', $result->query);

    }

    /**
     * 'in' clause syntax
     * @test
     */
    function in_clause_syntax()
    {
        $result = AQB::in('users')->get();
        self::assertEquals('IN users', $result->query);

        $result = AQB::in('1..2')->get();
        self::assertEquals('IN 1..2', $result->query);

        $result = AQB::in([1, 2, 3, 4])->get();
        self::assertEquals('IN [1,2,3,4]', $result->query);

        $result = AQB::for('u')->in(AQB::for('u')->in([1,2,3,4])->return('u'))->get();
        self::assertEquals('FOR u IN (FOR u IN [1,2,3,4] RETURN u)', $result->query);
    }

    /**
     * 'return' statement Syntax
     * @test
     */
    function return_statement_syntax()
    {
        $result = AQB::return('u.name')->get();

        self::assertEquals('RETURN u.name', $result->query);

    }
}