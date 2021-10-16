<?php

namespace Tests\Unit\AQL;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasDocumentFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\normalizesFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\normalizesDocumentFunctions
 */
class DocumentFunctionsTest extends TestCase
{
    public function testAttributes()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'my-collection')->return($qb->attributes("doc"));

        self::assertEquals('FOR doc IN my-collection RETURN ATTRIBUTES(doc, false, false)', $qb->get()->query);
    }

    public function testKeepAttributes()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->keepAttributes("doc", ["_id", "_key", "foo", "bar"]));

        self::assertEquals('RETURN KEEP(doc, ["_id","_key","foo","bar"])', $qb->get()->query);
    }

    public function testKeepAttributesSingleAttribute()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->keepAttributes("doc", "_id"));

        self::assertEquals('RETURN KEEP(doc, ["_id"])', $qb->get()->query);
    }

    public function testMatches()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->matches(
            [
                'user1' => ['name' => 'Janet']
            ],
            [
                'user2' => ['name' => 'Tom']
            ]
        ));

        self::assertEquals(
            'RETURN MATCHES({"user1":{"name":"Janet"}}, {"user2":{"name":"Tom"}}, false)',
            $qb->get()->query
        );
    }

    public function testMerge()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->merge(
            [
                'user1' => ['name' => 'Janet']
            ],
            [
                'user2' => ['name' => 'Tom']
            ]
        ));

        self::assertEquals('RETURN MERGE({"user1":{"name":"Janet"}}, {"user2":{"name":"Tom"}})', $qb->get()->query);
    }

    public function testMergeWithArrayOfDocuments()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->merge(
            [
                ['foo' => 'bar'],
                [
                    'quux' => 'quetzalcoatl',
                    'ruled' => true
                ],
                [
                    'bar' => 'baz',
                    'foo' => 'done'
                ],
            ]
        ));
        self::assertEquals(
            'RETURN MERGE([{"foo":"bar"},{"quux":"quetzalcoatl","ruled":true},{"bar":"baz","foo":"done"}])',
            $qb->get()->query
        );
    }

    public function testParseIdentifier()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->parseIdentifier("_users/my-user"));

        self::assertEquals('RETURN PARSE_IDENTIFIER(@'
            . $qb->getQueryId() . '_1)', $qb->get()->query);
    }

    public function testUnset()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->unset("doc", ["_id", "_key", "foo", "bar"]));

        self::assertEquals('RETURN UNSET(doc, ["_id","_key","foo","bar"])', $qb->get()->query);
    }

    public function testUnsetSingleAttribute()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->unset("doc", "_id"));

        self::assertEquals('RETURN UNSET(doc, ["_id"])', $qb->get()->query);
    }
}
