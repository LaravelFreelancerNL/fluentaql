<?php

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\Facades\AQB;

/**
 * Class StructureTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses
 */
class AqlFunctionsTest extends TestCase
{
    /**
     * document function
     * @test
     */
    public function document_function()
    {
        $functionExpression = AQB::document('users', 'users/john');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DOCUMENT(users, "users/john")', (string) $functionExpression);

        $functionExpression = AQB::document('users', 'john');
        self::assertEquals('DOCUMENT(users, "john")', (string) $functionExpression);

        $functionExpression = AQB::document('users', ['users/john', 'users/amy']);
        self::assertEquals('DOCUMENT(users, ["users/john", "users/amy"])', (string) $functionExpression);

        $functionExpression = AQB::document('users', ['john', 'amy']);
        self::assertEquals('DOCUMENT(users, ["john", "amy"])', (string) $functionExpression);

        $functionExpression = AQB::document('users/john');
        self::assertEquals('DOCUMENT("users/john")', (string) $functionExpression);

        $functionExpression = AQB::document(['users/john', 'users/amy']);
        self::assertEquals('DOCUMENT(["users/john", "users/amy"])', (string) $functionExpression);
    }
}
