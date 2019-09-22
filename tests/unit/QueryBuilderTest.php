<?php

use LaravelFreelancerNL\FluentAQL\Expressions\BindExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\ListExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\LiteralExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\NumericExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\RangeExpression;
use LaravelFreelancerNL\FluentAQL\Facades\AQB;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Class StructureTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\QueryBuilder
 */
class QueryBuilderTest extends TestCase
{

    /**
     * facade
     * @test
     */
    public function facade()
    {
        $aqb = AQB::get();

        self::assertInstanceOf(QueryBuilder::class, $aqb);
    }


    /**
     * get
     * @test
     */
    public function get()
    {
        $result = AQB::get();

        self::assertInstanceOf(QueryBuilder::class, $result);
    }

    /**
     * is sub query
     * @test
     */
    public function is_sub_query()
    {
        $query = AQB::for('u', 'users')->return('u');
        $result = $query->get();
        self::assertEquals('FOR u IN users RETURN u', $result->query);

        $query->setSubQuery();
        $result = $query->get();
        self::assertEquals('(FOR u IN users RETURN u)', $result->query);
    }

    /**
     * normalize argument
     * @test
     */
    public function normalize_argument()
    {
        $result = AQB::normalizeArgument(['col`1'], ['list', 'query']);
        self::assertInstanceOf(ListExpression::class, $result);

        $result = AQB::normalizeArgument('col`1', ['list', 'query', 'bind']);
        self::assertInstanceOf(BindExpression::class, $result);

        $result = AQB::normalizeArgument('1..2', ['list', 'query', 'range']);
        self::assertInstanceOf(RangeExpression::class, $result);

        $result = AQB::normalizeArgument(50, ['list', 'numeric', 'range']);
        self::assertInstanceOf(NumericExpression::class, $result);

        $result = AQB::normalizeArgument(+0123.45e6, ['list', 'numeric', 'range']);
        self::assertInstanceOf(NumericExpression::class, $result);


        $result = AQB::normalizeArgument(AQB::for('u', 'users')->return('u'), ['list', 'query', 'range']);
        self::assertInstanceOf(\LaravelFreelancerNL\FluentAQL\Expressions\QueryExpression::class, $result);
    }

    /**
     * clear commands
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
     * bind
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
        self::assertInstanceOf(\LaravelFreelancerNL\FluentAQL\Expressions\BindExpression::class, $bind);
        self::assertEquals('@1_1', (string) $bind);
        
        $bindings = $qb->getBinds();
        self::arrayHasKey('1_1');
        self::assertIsString($bindings['1_1']);
        self::assertEquals(121, strlen($bindings['1_1']));
    }
}
