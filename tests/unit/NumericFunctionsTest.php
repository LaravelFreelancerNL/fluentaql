<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\hasNumericFunctions
 */
class NumericFunctionsTest extends TestCase
{
    public function testAverage()
    {
        $qb = (new QueryBuilder())->let('x', [1, 2, 3, 4]);
        $functionExpression = $qb->average('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('AVERAGE(x)', (string) $functionExpression);
    }

    public function testAvg()
    {
        $qb = (new QueryBuilder())->let('x', [1, 2, 3, 4]);
        $functionExpression = $qb->avg('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('AVERAGE(x)', (string) $functionExpression);
    }

    public function testMax()
    {
        $qb = (new QueryBuilder())->let('x', [1, 2, 3, 4]);
        $functionExpression = $qb->max('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('MAX(x)', (string) $functionExpression);
    }

    public function testMin()
    {
        $qb = (new QueryBuilder())->let('x', [1, 2, 3, 4]);
        $functionExpression = $qb->min('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('MIN(x)', (string) $functionExpression);
    }

    public function testRand()
    {
        $qb = (new QueryBuilder())->let('x', [1, 2, 3, 4]);
        $functionExpression = $qb->rand();
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('RAND()', (string) $functionExpression);
    }

    public function testSum()
    {
        $qb = (new QueryBuilder())->let('x', [1, 2, 3, 4]);
        $functionExpression = $qb->sum('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('SUM(x)', (string) $functionExpression);
    }
}
