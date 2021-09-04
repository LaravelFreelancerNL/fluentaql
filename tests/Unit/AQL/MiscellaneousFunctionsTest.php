<?php

namespace Tests\Unit\AQL;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class StructureTest.
 *
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasMiscellaneousFunctions
 */
class MiscellaneousFunctionsTest extends TestCase
{
    public function testDocumentFunction()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->document('users', 'users/john'));
        self::assertEquals('RETURN DOCUMENT(users, "users/john")', $qb->get()->query);

        $qb = new QueryBuilder();
        $qb->return($qb->document('users', 'john'));
        self::assertEquals('RETURN DOCUMENT(users, "john")', $qb->get()->query);

        $qb = new QueryBuilder();
        $qb->return($qb->document('users/john', 'users/amy'));
        self::assertEquals('RETURN DOCUMENT("users/john", "users/amy")', $qb->get()->query);

        $qb = new QueryBuilder();
        $qb->return($qb->document('users', ['john', 'amy']));
        self::assertEquals('RETURN DOCUMENT(users, ["john","amy"])', $qb->get()->query);

        $qb = new QueryBuilder();
        $qb->return($qb->document('users/john'));
        self::assertEquals('RETURN DOCUMENT("users/john")', $qb->get()->query);

        $qb = new QueryBuilder();
        $qb->return($qb->document(['users/john', 'users/amy']));
        self::assertEquals('RETURN DOCUMENT(["users/john","users/amy"])', $qb->get()->query);
    }
}
