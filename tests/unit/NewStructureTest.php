<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit;

use LaravelFreelancerNL\FluentAQL\Expressions\BindExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class StructureTest.
 *
 * @covers \LaravelFreelancerNL\FluentAQL\QueryBuilder
 */
class NewStructureTest extends TestCase
{
    public function testForClauseSyntax()
    {
        $result = (new QueryBuilder())
            ->for('u', 'users')
            ->get();

        self::assertEquals('FOR u IN users', $result->query);

        $result = (new QueryBuilder())
            ->for('u')
            ->get();
        self::assertEquals('FOR u IN', $result->query);

        $result = (new QueryBuilder())
            ->for(['v', 'e', 'p'], 'graph')
            ->get();
        self::assertEquals('FOR v, e, p IN graph', $result->query);

        $result = (new QueryBuilder())
            ->for('i', '1..3')
            ->get();
        self::assertEquals('FOR i IN 1..3', $result->query);

        $result = (new QueryBuilder())
            ->for('i', [1,2,3,4])
            ->get();
        self::assertEquals('FOR i IN [1,2,3,4]', $result->query);
    }

    public function testReturnSyntax()
    {
        $result = (new QueryBuilder())
            ->return('NEW.key')
            ->get();
        self::assertEquals('RETURN NEW.key', $result->query);

        $result = (new QueryBuilder())
            ->return('u.name')
            ->get();
        self::assertEquals('RETURN @' . $result->getQueryId() . '_1', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->return('u.name')
            ->get();
        self::assertEquals('FOR u IN Users RETURN u.name', $result->query);

        $result = (new QueryBuilder())
            ->return('1 + 1')
            ->get();
        self::assertEquals('RETURN @' . $result->getQueryId() . '_1', $result->query);

        $result = (new QueryBuilder())
            ->return('1 + 1', true)
            ->get();
        self::assertEquals('RETURN DISTINCT @' . $result->getQueryId() . '_1', $result->query);
    }

    public function testFilterSyntax()
    {
        $result = (new QueryBuilder())
            ->for('u', 'users')
            ->filter('u.active', '==', 'true')
            ->get();
        self::assertEquals('FOR u IN users FILTER u.active == true', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->filter('u.active', '==', 'true', 'OR')
            ->get();
        self::assertEquals('FOR u IN Users FILTER u.active == true', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->filter('u.active', '==', true)
            ->get();
        self::assertEquals('FOR u IN Users FILTER u.active == true', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->filter('u.active', '==', null)
            ->get();
        self::assertEquals('FOR u IN Users FILTER u.active == null', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->filter([['u.active', '==', 'true'], ['u.age', '>', 18 ]])
            ->get();
        self::assertEquals('FOR u IN Users FILTER u.active == true AND u.age > 18', $result->query);
    }

    public function testLetStatement()
    {
        $result = (new QueryBuilder())
            ->let('x', '{
          "name": "Catelyn",
          "surname": "Stark",
          "alive": false,
          "age": 40,
          "traits": [
            "D",
            "H",
            "C"
          ]
        }')->get();
        self::assertEquals('LET x = @' . $result->getQueryId() . '_1', $result->query);

        $qb = new QueryBuilder();
        $object = new \stdClass();
        $object->name = 'Catelyn';
        $object->surname = 'Stark';
        $object->alive = false;
        $object->age = 40;
        $object->traits = ['D', 'H', 'C'];
        $result = $qb->let('x', $object)
            ->get();
        self::assertEquals(
            'LET x = {"name":"Catelyn","surname":"Stark","alive":false,"age":40,"traits":[@'
            . $result->getQueryId()
            . '_1,@'
            . $result->getQueryId()
            . '_2,@'
            . $result->getQueryId()
            . '_3]}',
            $result->query
        );

        $result = (new QueryBuilder())->let('x', 'y')
            ->get();
        self::assertEquals('LET x = @' . $result->getQueryId() . '_1', $result->query);
    }

    public function testSimpleFluentQuery()
    {
        $result = (new QueryBuilder())
            ->for('u', 'users')
            ->return('1 + 1', true)
            ->get();
        self::assertEquals('FOR u IN users RETURN DISTINCT @' . $result->getQueryId() . '_1', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'users')
            ->filter('u.active', '==', true)
            ->return('u', true)
            ->get();

        self::assertEquals('FOR u IN users FILTER u.active == true RETURN DISTINCT u', $result->query);
    }

    public function testSimpleSubQuery()
    {
        $subQuery = (new QueryBuilder())
            ->for('u', 'users')
            ->filter('u.active', '==', 'true')
            ->return('u._key')
            ->get();
        self::assertEquals(
            'FOR u IN users FILTER u.active == true RETURN u._key'
            , $subQuery->query
        );

        $result = (new QueryBuilder())
            ->for('u', 'users')
            ->filter('u._key', '==', $subQuery)
            ->return('u')
            ->get();

        self::assertEquals(
            'FOR u IN users FILTER u.active == true RETURN u._key'
            , $subQuery->query
        );

        self::assertEquals(
            'FOR u IN users FILTER u._key == (FOR u IN users FILTER u.active == true RETURN u._key) RETURN u'
            , $result->query
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
            'FOR u IN users FILTER u.active == @' . $subQuery->getQueryId() . '_1 RETURN u._key'
            , $subQuery->query
        );

        $result = (new QueryBuilder())
            ->for('u', 'users')
            ->filter('u._key', '==', $subQuery)
            ->return('u')
            ->get();

        self::assertEquals(
            'FOR u IN users FILTER u._key == (FOR u IN users FILTER u.active == @' . $subQuery->getQueryId() . '_1 RETURN u._key) RETURN u'
            , $result->query
        );

        self::assertArrayHasKey($subQuery->getQueryId() . '_1', $result->binds);
    }

    public function testSubQueryWithManyToManyJoin()
    {
//        FOR b IN books " +
//........>"  LET a = (FOR x IN b.authors " +
//........>"             FOR a IN authors FILTER x == a._id RETURN a) " +
//........>"   RETURN { book: b, authors: a }"
//
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
            . ' RETURN {"book":b,"authors":a}'
            , $result->query
        );
    }

}
