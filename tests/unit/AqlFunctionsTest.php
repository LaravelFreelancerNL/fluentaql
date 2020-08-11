<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class StructureTest.
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses
 */
class AqlFunctionsTest extends TestCase
{
    public function testDocumentFunction()
    {
        $functionExpression = (new QueryBuilder())->document('users', 'users/john');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DOCUMENT(users, "users/john")', (string) $functionExpression);

        $functionExpression = (new QueryBuilder())->document('users', 'john');
        self::assertEquals('DOCUMENT(users, "john")', (string) $functionExpression);

        $functionExpression = (new QueryBuilder())->document('users', ['users/john', 'users/amy']);
        self::assertEquals('DOCUMENT(users, ["users/john","users/amy"])', (string) $functionExpression);

        $functionExpression = (new QueryBuilder())->document('users', ['john', 'amy']);
        self::assertEquals('DOCUMENT(users, ["john","amy"])', (string) $functionExpression);

        $functionExpression = (new QueryBuilder())->document('users/john');
        self::assertEquals('DOCUMENT("users/john")', (string) $functionExpression);

        $functionExpression = (new QueryBuilder())->document(['users/john', 'users/amy']);
        self::assertEquals('DOCUMENT(["users/john","users/amy"])', (string) $functionExpression);
    }
}
