<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasSupportCommands
 */
class SupportCommandsTest extends TestCase
{
    public function testRawClause()
    {
        $result = (new QueryBuilder())
            ->raw('FOR user IN users FILTER u.email == "test@test.com"')
            ->get();
        self::assertEquals('FOR user IN users FILTER u.email == "test@test.com"', $result->query);
    }

    public function testRawAqlClauseWithBind()
    {
        $result = (new QueryBuilder())
            ->raw('FOR user IN users FILTER u.email == @email_address', ['email_address' => "test@test.com"])
            ->get();
        self::assertEquals('FOR user IN users FILTER u.email == @email_address', $result->query);
        self::assertEquals('test@test.com', $result->binds['email_address']);
    }

    public function testRawAqlClauseWithCollections()
    {
        $result = (new QueryBuilder())
            ->raw('FOR user IN users FILTER u.email == "test@test.com"', [], ['read' => ['users']])
            ->get();
        self::assertEquals('FOR user IN users FILTER u.email == "test@test.com"', $result->query);
        self::assertEquals('users', $result->collections['read'][0]);
    }

    public function testRawAqlExpression()
    {
        $qb = (new QueryBuilder());
        $qb->for('user', 'users')
            ->filter('user.age', '==', $qb->rawExpression('5 * 4'))
            ->get();
        self::assertEquals('FOR user IN users FILTER user.age == 5 * 4', $qb->query);
    }


    public function testRawAqlExpressionWithBind()
    {
        $qb = (new QueryBuilder());
        $qb->for('user', 'users')
            ->filter(
                'user.age',
                '==',
                $qb->rawExpression('5 * 4', ['email_address' => "test@test.com"])
            )
            ->get();
        self::assertEquals('FOR user IN users FILTER user.age == 5 * 4', $qb->query);
        self::assertEquals('test@test.com', $qb->binds['email_address']);
    }

    public function testRawAqlExpressionWithCollections()
    {
        $qb = (new QueryBuilder());
        $qb->for('user', 'users')
            ->filter('user.age', '==', $qb->rawExpression('5 * 4', [], ['read' => ['users']]))
            ->get();
        self::assertEquals('FOR user IN users FILTER user.age == 5 * 4', $qb->query);
        self::assertEquals('users', $qb->collections['read'][0]);
    }
}
