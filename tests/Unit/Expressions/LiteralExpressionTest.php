<?php

namespace Tests\Unit\Expressions;

use LaravelFreelancerNL\FluentAQL\Expressions\AttributeExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\BindExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\LiteralExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class QueryExpressionTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Expressions\LiteralExpression
 * @covers \LaravelFreelancerNL\FluentAQL\Expressions\AttributeExpression
 * @covers \LaravelFreelancerNL\FluentAQL\Expressions\BindExpression
 */
class LiteralExpressionTest extends TestCase
{
    public function testLiteralExpressionWithString()
    {
        $qb = new QueryBuilder();
        $expression = new LiteralExpression('a string');
        $result = $expression->compile($qb);

        self::assertEquals('a string', $result);
    }

    public function testLiteralExpressionWithQuotedString()
    {
        $qb = new QueryBuilder();
        $expression = new LiteralExpression('a string with a single "double quote');
        $result = $expression->compile($qb);

        self::assertEquals('a string with a single "double quote', $result);
    }

    public function testLiteralExpressionWithZero()
    {
        $qb = new QueryBuilder();
        $expression = new LiteralExpression(0);
        $result = $expression->compile($qb);

        self::assertEquals('0', $result);
    }

    /**
     * @covers \LaravelFreelancerNL\FluentAQL\Expressions\AttributeExpression
     */
    public function testAttributeExpression()
    {
        $qb = new QueryBuilder();
        $expression = new AttributeExpression('user');
        $result = $expression->compile($qb);

        self::assertEquals('user', $result);
    }

    /**
     * @covers \LaravelFreelancerNL\FluentAQL\Expressions\BindExpression
     */
    public function testBindExpression()
    {
        $qb = new QueryBuilder();
        $expression = new BindExpression('@bind-var');
        $result = $expression->compile($qb);

        self::assertEquals('@bind-var', $result);
    }
}
