<?php

namespace Tests\Unit\AQL;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasArrayFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\normalizesArrayFunctions
 */
class ArrayFunctionsTest extends TestCase
{
    public function testAppend()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->append([1, 2, 3, 4], [5,6]));
        self::assertEquals('RETURN APPEND([1,2,3,4], [5,6])', $qb->get()->query);
    }

    public function testAppendUnique()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->append([1, 2, 3, 4], [5,6], true));
        self::assertEquals('RETURN APPEND([1,2,3,4], [5,6], true)', $qb->get()->query);
    }

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

    public function testFlatten()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->flatten([ 1, 2, [ 3, 4 ], 5, [ 6, 7 ], [ 8, [ 9, 10 ] ] ]));
        self::assertEquals('RETURN FLATTEN([1,2,[3,4],5,[6,7],[8,[9,10]]], 1)', $qb->get()->query);
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

    public function testShift()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->shift([1,3,4,5]));
        self::assertEquals('RETURN SHIFT([1,3,4,5])', $qb->get()->query);
    }

    public function testUnique()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->unique([ 1,2,2,3,3,3,4,4,4,4,5,5,5,5,5 ]));
        self::assertEquals('RETURN UNIQUE([1,2,2,3,3,3,4,4,4,4,5,5,5,5,5])', $qb->get()->query);
    }
}
