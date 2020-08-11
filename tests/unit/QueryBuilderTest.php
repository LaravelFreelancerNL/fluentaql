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
class QueryBuilderTest extends TestCase
{

    public function testFacade()
    {
        $aqb = (new QueryBuilder())->get();

        self::assertInstanceOf(QueryBuilder::class, $aqb);
    }

    public function testGet()
    {
        $result = (new QueryBuilder())->get();

        self::assertInstanceOf(QueryBuilder::class, $result);
    }

    public function testGetQueryId()
    {
        $qb = new QueryBuilder();
        $id = $qb->getQueryId();

        self::assertEquals(spl_object_id($qb), $id);
    }

    public function testClearCommands()
    {
        $queryBuilder = (new QueryBuilder())->for('u', 'users')->return('u');
        self::assertCount(2, $queryBuilder->getCommands());

        $queryBuilder->clearCommands();
        self::assertCount(0, $queryBuilder->getCommands());
    }

    public function testToAql()
    {
        $query = (new QueryBuilder())->for('u', 'users')->return('u')->toAql();
        self::assertEquals('FOR u IN users RETURN u', $query);
    }

    public function testBind()
    {
        $qb = new QueryBuilder();

        $bind = $qb->bind('{
  "name": "Catelyn",
  "surname": "Stark",
  "alive": false,
  "age": 40,
  "traits": [
    "D",
    "H",
    "C"
  ]
}');
        self::assertInstanceOf(BindExpression::class, $bind);
        self::assertEquals('@' . $qb->getQueryId() . '_1', (string) $bind);

        self::arrayHasKey($qb->getQueryId() . '_1');
        self::assertIsString($qb->binds[$qb->getQueryId() . '_1']);
        self::assertEquals(121, strlen($qb->binds[$qb->getQueryId() . '_1']));
    }

    public function testRegisterCollections()
    {
        $qb = (new QueryBuilder())->registerCollections('Characters');
        self::assertArrayHasKey('write', $qb->collections);
        self::assertContains('Characters', $qb->collections['write']);

        $qb = $qb->registerCollections('Traits', 'read');
        self::assertArrayHasKey('read', $qb->collections);
        self::assertContains('Traits', $qb->collections['read']);

        $qb = $qb->registerCollections('Traits', 'exclusive');
        self::assertArrayHasKey('exclusive', $qb->collections);
        self::assertContains('Traits', $qb->collections['exclusive']);
    }
}
