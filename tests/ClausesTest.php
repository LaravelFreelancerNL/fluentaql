<?php

/**
 * Class StructureTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses
 */
class ClausesTest extends TestCase
{
    /**
     * for query syntax
     * @test
     */
    function for_query_syntax()
    {
        $result = $this->aqb->for('u', 'users')->get();

        self::assertEquals('FOR u IN users', $result->query);
    }

    /**
     * returnSyntax
     * @test
     */
    function return_syntax()
    {
        $result = $this->aqb->return('u.name')->get();

        self::assertEquals('RETURN u.name', $result->query);

    }
}