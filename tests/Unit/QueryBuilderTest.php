<?php

namespace Tests\Unit;

use LaravelFreelancerNL\FluentAQL\Clauses\ForClause;
use LaravelFreelancerNL\FluentAQL\Clauses\ReturnClause;
use LaravelFreelancerNL\FluentAQL\Exceptions\BindException;
use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
use LaravelFreelancerNL\FluentAQL\Expressions\BindExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\LiteralExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\QueryBuilder
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\ValidatesReferences
 */
class QueryBuilderTest extends TestCase
{
    public function testGetCommand()
    {
        $result = (new QueryBuilder());
        $result->addCommand(new ForClause(['u'], 'users'));
        $command = $result->getCommand(0);
        self::assertInstanceOf(ForClause::class, $command);
    }

    public function testGetLastCommand()
    {
        $result = (new QueryBuilder());
        $result->addCommand(new ForClause(['u'], 'users'));
        $result->addCommand(new ReturnClause('u'));
        $command = $result->getCommand();
        self::assertInstanceOf(ReturnClause::class, $command);
    }

    public function testGetCommands()
    {
        $result = (new QueryBuilder());
        $result->addCommand(new ForClause(['u'], 'users'));
        $commands = $result->getCommands();
        self::assertInstanceOf(ForClause::class, $commands[0]);
    }

    public function testRemoveCommand()
    {
        $qb = (new QueryBuilder());
        $qb->addCommand(new ForClause(['u'], 'users'));
        $command = $qb->getCommand(0);
        self::assertInstanceOf(ForClause::class, $command);
        $qb->removeCommand();
        self::assertEmpty($qb->getCommands());
    }

    public function testRemoveCommandWithIndex()
    {
        $qb = (new QueryBuilder());
        $qb->addCommand(new ForClause(['u'], 'users'));
        $command = $qb->getCommand(0);
        self::assertInstanceOf(ForClause::class, $command);
        $qb->removeCommand(0);
        self::assertEmpty($qb->getCommands());
    }


    public function testRemoveNonExistingCommand()
    {
        $qb = (new QueryBuilder());
        $qb->addCommand(new ForClause(['u'], 'users'));

        $result = $qb->removeCommand(50);

        self::assertFalse($result);
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
        self::assertEquals('@' . $qb->getQueryId() . '_1', $bind->compile($qb));

        self::arrayHasKey($qb->getQueryId() . '_1');
        self::assertIsString($qb->binds[$qb->getQueryId() . '_1']);
        self::assertEquals(121, strlen($qb->binds[$qb->getQueryId() . '_1']));
    }

    public function testBindCollection()
    {
        $qb = new QueryBuilder();

        $bind = $qb->bindCollection('users');
        self::assertInstanceOf(BindExpression::class, $bind);
        self::assertEquals('@@' . $qb->getQueryId() . '_1', $bind->compile($qb));

        self::arrayHasKey($qb->getQueryId() . '_1');
        self::assertIsString($qb->binds[$qb->getQueryId() . '_1']);

        self::assertEquals('users', $qb->binds[$qb->getQueryId() . '_1']);
    }

    public function testInvalidBindVariable()
    {
        $qb = new QueryBuilder();
        $this->expectException(BindException::class);
        $qb->bind('my data', 'invalid bind var');
    }

    public function testInvalidFunctionParameter()
    {
        $qb = new QueryBuilder();
        $this->expectException(ExpressionTypeException::class);
        $qb->return($qb->analyzer('text', 0123))->get();
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

    public function testRegisterVariable()
    {
        $qb = new QueryBuilder();
        $qb->registerVariable('varName');

        $this->assertContains('varName', $qb->getVariables());
    }

    public function testRegisterExpressionVariable()
    {
        $qb = new QueryBuilder();
        $qb->registerVariable(new LiteralExpression('varName'));
        $this->assertContains('varName', $qb->getVariables());
    }

    public function testUnsetNullValues()
    {

        $qb = new QueryBuilder();
        $results = $qb->unsetNullValues([
            null,
            false,
            1,
            null,
            'test',
            null
        ]);

        $this->assertCount(3, $results);
        $this->assertSame([
            1 => false,
            2 => 1,
            4 => 'test'
        ], $results);
    }
}
