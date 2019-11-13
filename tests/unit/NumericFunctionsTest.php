<?php

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\Facades\AQB;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\API\hasNumericFunctions
 */
class NumericFunctionsTest extends TestCase
{
    public function test_average()
    {
        $qb = AQB::let('x', [1,2,3,4]);
        $functionExpression = $qb->average('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('AVERAGE(x)', (string) $functionExpression);
    }

    public function test_avg()
    {
        $qb = AQB::let('x', [1,2,3,4]);
        $functionExpression = $qb->avg('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('AVERAGE(x)', (string) $functionExpression);
    }

    public function test_max()
    {
        $qb = AQB::let('x', [1,2,3,4]);
        $functionExpression = $qb->max('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('MAX(x)', (string) $functionExpression);
    }

    public function test_min()
    {
        $qb = AQB::let('x', [1,2,3,4]);
        $functionExpression = $qb->min('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('MIN(x)', (string) $functionExpression);
    }

    public function test_rand()
    {
        $qb = AQB::let('x', [1,2,3,4]);
        $functionExpression = $qb->rand();
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('RAND()', (string) $functionExpression);
    }

    public function test_sum()
    {
        $qb = AQB::let('x', [1,2,3,4]);
        $functionExpression = $qb->sum('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('SUM(x)', (string) $functionExpression);
    }
}
