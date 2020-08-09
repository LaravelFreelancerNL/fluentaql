<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\hasArrayFunctions
 */
class ArrayFunctionsTest extends TestCase
{
    public function testCount()
    {
        $qb = (new QueryBuilder())->let('x', [1, 2, 3, 4]);
        $functionExpression = $qb->count('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('LENGTH(x)', (string) $functionExpression);
    }

    public function testCountDistinct()
    {
        $qb = (new QueryBuilder())->for('x', 'numbers');
        $functionExpression = $qb->countDistinct('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('COUNT_DISTINCT(x)', (string) $functionExpression);
    }

    public function testFirst()
    {
        $qb = (new QueryBuilder())->let('x', [1, 2, 3, 4]);
        $functionExpression = $qb->first('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('FIRST(x)', (string) $functionExpression);
    }

    public function testLast()
    {
        $qb = (new QueryBuilder())->let('x', [1, 2, 3, 4]);
        $functionExpression = $qb->last('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('LAST(x)', (string) $functionExpression);
    }

    public function testLength()
    {
        $qb = (new QueryBuilder())->let('x', [1, 2, 3, 4]);
        $functionExpression = $qb->length('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('LENGTH(x)', (string) $functionExpression);
    }
}
