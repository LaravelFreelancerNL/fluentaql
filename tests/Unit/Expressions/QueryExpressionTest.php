<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit\Expressions;

use LaravelFreelancerNL\FluentAQL\Expressions\QueryExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class QueryExpressionTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Expressions\QueryExpression
 */
class QueryExpressionTest extends TestCase
{
    public function testQueryExpression()
    {
        $qb = new QueryBuilder();
        $qb->for('u', 'users')->return('u');
        $qe = new QueryExpression($qb);
        $result = $qe->compile($qb);

        self::assertEquals('(FOR u IN users RETURN u)', $result);
    }
}
