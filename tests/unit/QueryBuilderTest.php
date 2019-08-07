<?php

use LaravelFreelancerNL\FluentAQL\Facades\AQB;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Class StructureTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\QueryBuilder
 */
class QueryBuilderTest extends TestCase
{

//    /**
//     * facade
//     * @test
//     */
//    function facade()
//    {
//        $aqb = AQB::for('u')->in('users')->return('u')->get();
//
//        self::assertInstanceOf(QueryBuilder::class, $aqb);
//    }
//
//    /**
//     * get
//     * @test
//     */
//    function get()
//    {
//        $result = AQB::get();
//
//        self::assertInstanceOf(QueryBuilder::class, $result);
//    }
//
//    /**
//     * is sub query
//     * @test
//     */
//    function is_sub_query()
//    {
//        $queryBuilder = AQB::for('u')->in('users')->return('u')->get();
//        self::assertEquals('FOR u IN users RETURN u', $queryBuilder->query);
//
//        $queryBuilder = AQB::setSubQuery()->for('u')->in('users')->return('u')->get();
//        self::assertEquals('(FOR u IN users RETURN u)', $queryBuilder->query);
//    }
//
//    /**
//     * clear commands
//     * @test
//     */
//    function clear_commands()
//    {
//        $queryBuilder = AQB::for('u')->in('users')->return('u');
//        self::assertCount(3, $queryBuilder->getCommands());
//
//        $queryBuilder->clearCommands();
//        self::assertCount(0,  $queryBuilder->getCommands());
//    }
//
//    /**
//     * bind
//     * @test
//     */
//    function bind()
//    {
//        $bind = AQB::bind("'s ochtends is het buiten rustig");
//        self::assertInstanceOf(\LaravelFreelancerNL\FluentAQL\Expressions\BindingExpression::class, $bind);
//        self::assertEquals('@1', $bind);
//
//        $bind = AQB::bind("'s ochtends is het buiten rustig", 'aBindName');
//        self::assertEquals('@aBindName', $bind);
//
//        $bind = AQB::bind("'s ochtends is het buiten rustig", '@aBindName', 'collection');
//        self::assertEquals('@@aBindName', $bind);
//
//    }

}