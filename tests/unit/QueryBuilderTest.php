<?php

use LaravelFreelancerNL\FluentAQL\Expressions\BindExpression;
use LaravelFreelancerNL\FluentAQL\Facades\AQB;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Class StructureTest.
 *
 * @covers \LaravelFreelancerNL\FluentAQL\QueryBuilder
 */
class QueryBuilderTest extends TestCase
{
    /**
     * facade.
     * @test
     */
    public function facade()
    {
        $aqb = AQB::get();

        self::assertInstanceOf(QueryBuilder::class, $aqb);
    }

    /**
     * get.
     * @test
     */
    public function get()
    {
        $result = AQB::get();

        self::assertInstanceOf(QueryBuilder::class, $result);
    }

    /**
     * clear commands.
     * @test
     */
    public function clear_commands()
    {
        $queryBuilder = AQB::for('u', 'users')->return('u');
        self::assertCount(2, $queryBuilder->getCommands());

        $queryBuilder->clearCommands();
        self::assertCount(0, $queryBuilder->getCommands());
    }

    /**
     * toAql.
     * @test
     */
    public function to_aql()
    {
        $query = AQB::for('u', 'users')->return('u')->toAql();
        self::assertEquals('FOR u IN users RETURN u', $query);
    }

    /**
     * bind.
     * @test
     */
    public function bind()
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
        self::assertEquals('@1_1', (string) $bind);

        self::arrayHasKey('1_1');
        self::assertIsString($qb->binds['1_1']);
        self::assertEquals(121, strlen($qb->binds['1_1']));
    }

    /**
     * register collection.
     * @test
     */
    public function register_collections()
    {
        $qb = AQB::registerCollections('Characters');
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
