<?php

namespace Tests\Unit\Expressions;

use LaravelFreelancerNL\FluentAQL\Expressions\NullExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use Tests\TestCase;

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
}
