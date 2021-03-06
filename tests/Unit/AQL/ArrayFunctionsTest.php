<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit\Functions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasArrayFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\normalizesArrayFunctions
 */
class ArrayFunctionsTest extends TestCase
{
    public function testCount()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->count([1, 2, 3, 4]));
        self::assertEquals('LET x = LENGTH([1,2,3,4])', $qb->get()->query);
    }

    public function testCountDistinct()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->countDistinct([1, 2, 3, 4]));
        self::assertEquals('LET x = COUNT_DISTINCT([1,2,3,4])', $qb->get()->query);
    }

    public function testFirst()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->first([1, 2, 3, 4]));
        self::assertEquals('LET x = FIRST([1,2,3,4])', $qb->get()->query);
    }

    public function testLast()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->last([1, 2, 3, 4]));
        self::assertEquals('LET x = LAST([1,2,3,4])', $qb->get()->query);
    }

    public function testLength()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->length([1, 2, 3, 4]));
        self::assertEquals('LET x = LENGTH([1,2,3,4])', $qb->get()->query);
    }
}
