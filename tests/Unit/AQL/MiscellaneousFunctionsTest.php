<?php

namespace Tests\Unit\AQL;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasMiscellaneousFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\normalizesFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\normalizesMiscellaneousFunctions
 */
class MiscellaneousFunctionsTest extends TestCase
{
    public function testAssert()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->return($qb->assert('doc.text', '==', 'bar', 'Text is not the same'));

        self::assertEquals(
            'FOR doc IN viewName RETURN ASSERT(doc.text == "bar", @' . $qb->getQueryId() . '_1)',
            $qb->get()->query
        );
    }

    public function testAssertMultiplePredicates()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->return($qb->assert([
                ['doc.text', '==', 'foo'],
                ['doc.text', '==', 'bar', 'OR'],
            ], 'text_en'));

        self::assertEquals(
            'FOR doc IN viewName RETURN ASSERT((doc.text == "foo" OR doc.text == "bar"), @'
            . $qb->getQueryId() . '_1)',
            $qb->get()->query
        );
    }

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

    public function testCurrentDatabase()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->currentDatabase());
        self::assertEquals('RETURN CURRENT_DATABASE()', $qb->get()->query);
    }

    public function testCurrentUser()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->currentUser());
        self::assertEquals('RETURN CURRENT_USER()', $qb->get()->query);
    }

    public function testFirstDocument()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->firstDocument('users'));
        self::assertEquals('RETURN FIRST_DOCUMENT(@' . $qb->getQueryId() . '_1)', $qb->get()->query);
    }

    public function testWarn()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->return($qb->warn('doc.text', '==', 'bar', 'Text is not the same'));

        self::assertEquals(
            'FOR doc IN viewName RETURN WARN(doc.text == "bar", @' . $qb->getQueryId() . '_1)',
            $qb->get()->query
        );
    }

    public function testWarnMultiplePredicates()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->return($qb->warn([
                ['doc.text', '==', 'foo'],
                ['doc.text', '==', 'bar', 'OR'],
            ], 'text_en'));

        self::assertEquals(
            'FOR doc IN viewName RETURN WARN((doc.text == "foo" OR doc.text == "bar"), @'
            . $qb->getQueryId() . '_1)',
            $qb->get()->query
        );
    }
}
