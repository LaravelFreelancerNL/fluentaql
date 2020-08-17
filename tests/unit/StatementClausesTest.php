<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class StructureTest.
 *
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\hasStatementClauses.php
 */
class StatementClausesTest extends TestCase
{
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

    public function testInsertStatement()
    {
        $result = (new QueryBuilder())->insert('{
          "name": "Catelyn",
          "surname": "Stark",
          "alive": false,
          "age": 40,
          "traits": [
            "D",
            "H",
            "C"
          ]
        }', 'Characters')->get();
        self::assertEquals('INSERT @' . $result->getQueryId() . '_1 IN Characters', $result->query);
    }

    public function testUdateStatement()
    {
        $result = (new QueryBuilder())
            ->for('u', 'users')
            ->update(
                'u',
                '{ name: CONCAT(u.firstName, " ", u.lastName) }',
                'users'
            )->get();
        self::assertEquals('FOR u IN users UPDATE u WITH @' . $result->getQueryId() . '_1 IN users', $result->query);
    }

    public function testUpdateMaintainsNullValue()
    {
        $data = new \stdClass();
        $data->name['first_name'] = null;
        $data->name['last_name'] = null;
        $result = (new QueryBuilder())
            ->for('u', 'users')
            ->update('u', $data, 'users')
            ->get();
        self::assertEquals(
            'FOR u IN users UPDATE u WITH {"name":{"first_name":null,"last_name":null}} IN users',
            $result->query
        );
    }

    public function testReplaceStatement()
    {
        $result = (new QueryBuilder())
            ->for('u', 'users')
            ->replace(
                'u',
                '{ _key: u._key, name: CONCAT(u.firstName, u.lastName), status: u.status }',
                'users'
            )
            ->get();
        self::assertEquals('FOR u IN users REPLACE u WITH @' . $result->getQueryId() . '_1 IN users', $result->query);
    }

    public function testUpsertStatement()
    {
        $result = (new QueryBuilder())
            ->upsert(
                '{ name: "superuser" }',
                '{ name: "superuser", logins: 1, dateCreated: DATE_NOW() }',
                '{ logins: OLD.logins + 1 }',
                'users'
            )->get();
        self::assertEquals(
            'UPSERT @'
            . $result->getQueryId()
            . '_1 INSERT @'
            . $result->getQueryId()
            . '_2 UPDATE @'
            . $result->getQueryId()
            . '_3 IN users',
            $result->query
        );

        $result = (new QueryBuilder())
            ->upsert(
                '{ name: "superuser" }',
                '{ name: "superuser", logins: 1, dateCreated: DATE_NOW() }',
                '{ logins: OLD.logins + 1 }',
                'users',
                true
            )->get();
        self::assertEquals(
            'UPSERT @'
            . $result->getQueryId()
            . '_1 INSERT @'
            . $result->getQueryId()
            . '_2 REPLACE @'
            . $result->getQueryId()
            . '_3 IN users',
            $result->query
        );
    }

    public function testRemoveStatement()
    {
        $result = (new QueryBuilder())
            ->for('u', 'users')
            ->remove('u', 'users')
            ->get();
        self::assertEquals('FOR u IN users REMOVE u IN users', $result->query);

        $result = (new QueryBuilder())
            ->remove('john', 'users')
            ->get();
        self::assertEquals('REMOVE "john" IN users', $result->query);

        $result = (new QueryBuilder())
            ->for('i', '1..1000')
            ->remove('{ _key: CONCAT(\'test\', i) }', 'users')
            ->get();
        self::assertEquals('FOR i IN 1..1000 REMOVE @' . $result->getQueryId() . '_1 IN users', $result->query);
    }
}
