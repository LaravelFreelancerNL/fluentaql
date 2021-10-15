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
}
