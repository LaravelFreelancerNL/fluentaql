<?php

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\Facades\AQB;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\API\hasArrayFunctions
 */
class ArrayFunctionsTest extends TestCase
{
    public function test_count()
    {
        $qb = AQB::let('x', [1,2,3,4]);
        $functionExpression = $qb->count('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('LENGTH(x)', (string) $functionExpression);
    }

    public function test_count_distinct()
    {
        $qb = AQB::for('x', 'numbers');
        $functionExpression = $qb->countDistinct('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('COUNT_DISTINCT(x)', (string) $functionExpression);
    }


    public function test_first()
    {
        $qb = AQB::let('x', [1,2,3,4]);
        $functionExpression = $qb->first('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('FIRST(x)', (string) $functionExpression);
    }

    public function test_last()
    {
        $qb = AQB::let('x', [1,2,3,4]);
        $functionExpression = $qb->last('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('LAST(x)', (string) $functionExpression);
    }

    public function test_length()
    {
        $qb = AQB::let('x', [1,2,3,4]);
        $functionExpression = $qb->length('x');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('LENGTH(x)', (string) $functionExpression);
    }
}
