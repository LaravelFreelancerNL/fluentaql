<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit\Functions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasArrayFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\normalizesArrayFunctions
 */
class OperatorExpressionsTest extends TestCase
{
    public function testIf()
    {
        $qb = new QueryBuilder();
        $qb->let('x', 5)->return($qb->if(['x', '==', '5'], true, false));
        self::assertEquals("LET x = 5 RETURN (x == 5) ? true : false", $qb->get()->query);
    }

    public function testCalc()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->calc(3, '*', 3));
        self::assertEquals("RETURN 3 * 3", $qb->get()->query);
    }

    public function testCalcWithEmbeddedCalc()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->calc(3, '+', $qb->calc(3, '*', 3)));
        self::assertEquals("RETURN 3 + (3 * 3)", $qb->get()->query);
    }
}
