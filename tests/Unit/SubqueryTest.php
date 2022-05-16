<?php

namespace Tests\Unit;

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
            ->filter('u.name', '==', 'some name')
            ->filter('u._key', '==', $subQuery)
            ->return('u')
            ->get();

        self::assertEquals(
            'FOR u IN users FILTER u.name == @' .
            $result->getQueryId() .
            '_1 FILTER u._key == (FOR u IN users FILTER u.active == @' .
                $subQuery->getQueryId() .
                '_1 RETURN u._key) RETURN u',
            $result->query
        );

        self::assertArrayHasKey($result->getQueryId() . '_1', $result->binds);
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

    public function testSubqueryWithLet()
    {
        $vertexGetQuery = (new QueryBuilder())
            ->for('v', '0..99')
            ->traverse('persons/123')
            ->edgeCollections('relations')
            ->return('v._key');

        $vertexRemovalQuery = (new QueryBuilder())
            ->for('vertexKey', 'verteces')
            ->remove('vertexKey', 'persons')
            ->get();

        $edgeGetQuery = (new QueryBuilder())
            ->for(['v', 'e'], '1..99')
            ->traverse('persons/123')
            ->edgeCollections('relations')
            ->return('e._key');

        $edgeRemovalQuery = (new QueryBuilder())
            ->for('edgeKey', 'edges')
            ->remove('edgeKey', 'relations');

        $deleteQuery = (new QueryBuilder())
            ->with('persons')
            ->let('verteces', $vertexGetQuery)
            ->let('edges', $edgeGetQuery)
            ->let('vertexRemovals', $vertexRemovalQuery)
            ->let('edgeRemovals', $edgeRemovalQuery)
            ->return(['vertexRemovals', 'edgeRemovals'])
            ->get();

        self::assertEquals(
            'WITH persons'
            . ' LET verteces = (FOR v IN 0..99 OUTBOUND "persons/123" relations RETURN v._key)'
            . ' LET edges = (FOR v, e IN 1..99 OUTBOUND "persons/123" relations RETURN e._key)'
            . ' LET vertexRemovals = (FOR vertexKey IN verteces REMOVE vertexKey IN persons)'
            . ' LET edgeRemovals = (FOR edgeKey IN edges REMOVE edgeKey IN relations)'
            . ' RETURN [vertexRemovals,edgeRemovals]',
            $deleteQuery->query
        );
    }
}
