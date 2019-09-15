<?php

use LaravelFreelancerNL\FluentAQL\Facades\AQB;

/**
 * Class StructureTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses
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
    }

    /**
     * 'for' statement syntax
     * @test
     */
    public function for_statement_syntax()
    {
        $result = AQB::for('u')->get();
        self::assertEquals('FOR u', $result->query);

        $result = AQB::for('v', 'e', 'p')->get();
        self::assertEquals('FOR v, e, p', $result->query);
    }

    /**
     * 'in' clause syntax
     * @test
     */
    public function in_clause_syntax()
    {
        $result = AQB::in('users')->get();
        self::assertEquals('IN users', $result->query);

        $result = AQB::in('1..2')->get();
        self::assertEquals('IN 1..2', $result->query);

        $result = AQB::in([1, 2, 3, 4])->get();
        self::assertEquals('IN [1, 2, 3, 4]', $result->query);
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
    }
}
