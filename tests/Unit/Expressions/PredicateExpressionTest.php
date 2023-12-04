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
        $expression = new PredicateExpression((new LiteralExpression('x')), '==', (new LiteralExpression(10)), 'AND');
        $expression->compile($qb);

        self::assertEquals('AND', $expression->logicalOperator);
    }

    public function testPredicateExpressionWithLogicalOperatorOr()
    {
        $qb = new QueryBuilder();
        $expression = new PredicateExpression((new LiteralExpression('x')), '==', (new LiteralExpression(10)), 'OR');
        $expression->compile($qb);

        self::assertEquals('OR', $expression->logicalOperator);
    }

    public function testPredicateExpressionLeftOperandOnly()
    {
        $qb = new QueryBuilder();
        $expression = new PredicateExpression((new LiteralExpression('x')));
        $result = $expression->compile($qb);

        self::assertEquals('x', $result);
    }

    public function testPredicateWithoutRightOperand()
    {
        $qb = new QueryBuilder();
        $expression = new PredicateExpression(
            (new LiteralExpression('x')),
            '==',
            null
        );
        $result = $expression->compile($qb);

        self::assertEquals('x == null', $result);
    }

    public function testGroupOfPredicateExpressions()
    {
        $qb = new QueryBuilder();
        $predicate1 = new PredicateExpression(
            (new LiteralExpression('u.name')),
            '==',
            'Cookie Monster'
        );
        $predicate2 = new PredicateExpression(
            (new LiteralExpression('u.age')),
            '==',
            27
        );

        $result = $qb->for('u', 'users')
            ->filter([$predicate1, $predicate2])
            ->return('u');

        self::assertEquals(
            'FOR u IN users FILTER u.name == @'.$qb->getQueryId().'_1 AND u.age == 27 RETURN u',
            $result->get()->query
        );
    }
}
