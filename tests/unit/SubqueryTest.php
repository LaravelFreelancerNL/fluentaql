<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

class SubqueryTest extends TestCase
{
    public function testSimpleSubQuery()
    {
        $subQuery = (new QueryBuilder())
            ->for('u', 'users')
            ->filter('u.active', '==', 'true')
            ->return('u._key')
            ->get();
        self::assertEquals(
            'FOR u IN users FILTER u.active == true RETURN u._key',
            $subQuery->query
        );

        $result = (new QueryBuilder())
            ->for('u', 'users')
            ->filter('u._key', '==', $subQuery)
            ->return('u')
            ->get();

        self::assertEquals(
            'FOR u IN users FILTER u.active == true RETURN u._key',
            $subQuery->query
        );

        self::assertEquals(
            'FOR u IN users FILTER u._key == (FOR u IN users FILTER u.active == true RETURN u._key) RETURN u',
            $result->query
        );
    }

    public function testSubQueryWithBinds()
    {
        $subQuery = (new QueryBuilder())
            ->for('u', 'users')
            ->filter('u.active', '==', 'something to bind')
            ->return('u._key')
            ->get();
        self::assertEquals(
            'FOR u IN users FILTER u.active == @' . $subQuery->getQueryId() . '_1 RETURN u._key',
            $subQuery->query
        );

        $result = (new QueryBuilder())
            ->for('u', 'users')
            ->filter('u._key', '==', $subQuery)
            ->return('u')
            ->get();

        self::assertEquals(
            'FOR u IN users FILTER u._key == (FOR u IN users FILTER u.active == @' .
                $subQuery->getQueryId() .
                '_1 RETURN u._key) RETURN u',
            $result->query
        );

        self::assertArrayHasKey($subQuery->getQueryId() . '_1', $result->binds);
    }

    public function testSubQueryWithManyToManyJoin()
    {
        $subQuery = (new QueryBuilder())
            ->for('x', 'b.authors')
            ->for('a', 'authors')
            ->filter('x', '==', 'a._id')
            ->return('a');

        $result = (new QueryBuilder())
            ->for('b', 'books')
            ->let('a', $subQuery)
            ->return(['book' => 'b', 'authors' => 'a'])
            ->get();


        self::assertEquals(
            'FOR b IN books LET a = (FOR x IN b.authors FOR a IN authors FILTER x == a._id RETURN a)'
            . ' RETURN {"book":b,"authors":a}',
            $result->query
        );
    }

    public function testSubQueryWithFunction()
    {
        $subQuery = (new QueryBuilder())
            ->for('x', 'b.authors')
            ->for('a', 'authors')
            ->filter('x', '==', 'a._id')
            ->return('a');

        $result = (new QueryBuilder());
        $result->for('b', 'books')
            ->let('a', $result->first($subQuery))
            ->return(['book' => 'b', 'authors' => 'a'])
            ->get();


        self::assertEquals(
            'FOR b IN books LET a = FIRST((FOR x IN b.authors FOR a IN authors FILTER x == a._id RETURN a))'
            . ' RETURN {"book":b,"authors":a}',
            $result->query
        );
    }
}
