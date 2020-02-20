<?php

use LaravelFreelancerNL\FluentAQL\Facades\AQB;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Class StructureTest.
 *
 * @covers \LaravelFreelancerNL\FluentAQL\API\hasStatementClauses.php
 */
class StatementClausesTest extends TestCase
{
    /**
     * Let statement.
     * @test
     */
    public function let_statement()
    {
        $result = AQB::let('x', '{
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
        self::assertEquals('LET x = @1_1', $result->query);

        $qb = new QueryBuilder();
        $object = new stdClass;
        $object->name = 'Catelyn';
        $object->surname = 'Stark';
        $object->alive = false;
        $object->age = 40;
        $object->traits = ['D', 'H', 'C'];
        $result = $qb->let('x', $object)->get();
        self::assertEquals('LET x = {"name":"Catelyn","surname":"Stark","alive":false,"age":40,"traits":[@1_1,@1_2,@1_3]}', $result->query);

        $result = AQB::let('x', 'y')->get();
        self::assertEquals('LET x = @1_1', $result->query);
    }

    /**
     * insert.
     * @test
     */
    public function insert()
    {
        $result = AQB::insert('{
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
        self::assertEquals('INSERT @1_1 IN Characters', $result->query);
    }

    /**
     * Update.
     * @test
     */
    public function update()
    {
        $result = AQB::for('u', 'users')->update('u', '{ name: CONCAT(u.firstName, " ", u.lastName) }', 'users')->get();
        self::assertEquals('FOR u IN users UPDATE u WITH @1_1 IN users', $result->query);
    }

    /**
     * replace.
     * @test
     */
    public function replace()
    {
        $result = AQB::for('u', 'users')->replace('u', '{ _key: u._key, name: CONCAT(u.firstName, u.lastName), status: u.status }', 'users')->get();
        self::assertEquals('FOR u IN users REPLACE u WITH @1_1 IN users', $result->query);
    }

    /**
     * remove.
     * @test
     */
    public function remove()
    {
        $result = AQB::for('u', 'users')->remove('u', 'users')->get();
        self::assertEquals('FOR u IN users REMOVE u IN users', $result->query);

        $result = AQB::remove('john', 'users')->get();
        self::assertEquals('REMOVE "john" IN users', $result->query);

        $result = AQB::for('i', '1..1000')->remove('{ _key: CONCAT(\'test\', i) }', 'users')->get();
        self::assertEquals('FOR i IN 1..1000 REMOVE @1_1 IN users', $result->query);
    }

    /**
     * Upsert.
     * @test
     */
    public function upsert()
    {
        $result = AQB::upsert('{ name: "superuser" }', '{ name: "superuser", logins: 1, dateCreated: DATE_NOW() }', '{ logins: OLD.logins + 1 }', 'users')->get();
        self::assertEquals('UPSERT @1_1 INSERT @1_2 UPDATE @1_3 IN users', $result->query);

        $result = AQB::upsert('{ name: "superuser" }', '{ name: "superuser", logins: 1, dateCreated: DATE_NOW() }', '{ logins: OLD.logins + 1 }', 'users', true)->get();
        self::assertEquals('UPSERT @1_1 INSERT @1_2 REPLACE @1_3 IN users', $result->query);
    }

    /**
     * Update.
     * @test
     */
    public function updateMaintainsNullValue()
    {
        $data = new stdClass();
        $data->name['first_name'] = null;
        $data->name['last_name'] = null;
        $result = AQB::for('u', 'users')->update('u', $data, 'users')->get();
        self::assertEquals('FOR u IN users UPDATE u WITH {"name":{"first_name":null,"last_name":null}} IN users', $result->query);
    }

}
