<?php

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\Facades\AQB;

/**
 * Class StructureTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses
 */
class NumericFunctionsTest extends TestCase
{
    public function test_max()
    {
        $qb = AQB::for('m', 'migrations');
        $functionExpression = $qb->max('m.batch');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('MAX(m.batch)', (string) $functionExpression);
    }
}
