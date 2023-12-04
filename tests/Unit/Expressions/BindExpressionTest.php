<?php

namespace Tests\Unit\Expressions;

use LaravelFreelancerNL\FluentAQL\Expressions\BindExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use Tests\TestCase;

/**
 * Class QueryExpressionTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Expressions\NullExpression
 */
class BindExpressionTest extends TestCase
{
    public function testBindExpression()
    {
        $bindVar = '@myBind';
        $data = 'myData';
        $qb = new QueryBuilder();
        $expression = new BindExpression($bindVar, $data);
        $result = $expression->compile($qb);

        self::assertEquals($bindVar, $result);
        self::assertEquals($data, $expression->getData());
    }

    public function testBindExpressionIsAddedToNewQuery()
    {
        $bindVar = '@myBind';
        $data = 'test';
        $bindExpression = new BindExpression($bindVar, $data);

        $qb = (new QueryBuilder())->filter('test', '==', $bindExpression);
        $qb->get();

        $this->assertCount(1, $qb->binds);
        $this->assertSame($data, array_shift($qb->binds));
    }
}
