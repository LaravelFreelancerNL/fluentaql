<?php

namespace Tests\Unit\AQL;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasMiscellaneousFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\normalizesMiscellaneousFunctions
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

    public function testFirstDocument()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->firstDocument('users'));
        self::assertEquals('RETURN FIRST_DOCUMENT(@' . $qb->getQueryId() . '_1)', $qb->get()->query);
    }
}
