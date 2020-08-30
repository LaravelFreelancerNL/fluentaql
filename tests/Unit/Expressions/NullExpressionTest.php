<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit\Expressions;

use LaravelFreelancerNL\FluentAQL\Expressions\NullExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class QueryExpressionTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Expressions\NullExpression
 */
class NullExpressionTest extends TestCase
{
    public function testNullExpression()
    {
        $qb = new QueryBuilder();
        $expression = new NullExpression();
        $result = $expression->compile($qb);

        self::assertEquals('null', $result);
    }

    public function testNullExpressionWithZero()
    {
        $qb = new QueryBuilder();
        $expression = new NullExpression(0);
        $result = $expression->compile($qb);

        self::assertEquals('null', $result);
    }

    public function testNullExpressionWithNullString()
    {
        $qb = new QueryBuilder();
        $expression = new NullExpression('null');
        $result = $expression->compile($qb);

        self::assertEquals('null', $result);
    }


}
