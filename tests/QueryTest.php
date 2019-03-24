<?php

/**
 * Class StructureTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Query
 */
class QueryTest extends TestCase
{
    /**
     * get
     * @test
     */
    function get()
    {
        $result = $this->aqb->get();

        self::assertInstanceOf(\LaravelFreelancerNL\FluentAQL\Query::class, $result);
    }
}