<?php

namespace Tests\Unit\Expressions;

use LaravelFreelancerNL\FluentAQL\Expressions\LiteralExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class QueryExpressionTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression
 */
class PredicateExpressionTest extends TestCase
{
    public function testPredicateExpression()
    {
        $qb = new QueryBuilder();
        $expression = new PredicateExpression((new LiteralExpression('x')), '==', (new LiteralExpression(10)));
        $result = $expression->compile($qb);

        self::assertEquals('x == 10', $result);
    }

    public function testPredicateExpressionWithLogicalOperatorAnd()
    {
        $qb = new QueryBuilder();
        $expression = new PredicateExpression((new LiteralExpression('x')), '==', (new LiteralExpression(10)), "AND");
        $expression->compile($qb);

        self::assertEquals('AND', $expression->logicalOperator);
    }

    public function testPredicateExpressionWithLogicalOperatorOr()
    {
        $qb = new QueryBuilder();
        $expression = new PredicateExpression((new LiteralExpression('x')), '==', (new LiteralExpression(10)), "OR");
        $expression->compile($qb);

        self::assertEquals('OR', $expression->logicalOperator);
    }
}
