<?php

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
    function document_function()
    {
        $queryBuilder = AQB::document('users', 'users/john')->get();
        self::assertEquals('DOCUMENT(users, "users/john")', $queryBuilder->query);

        $queryBuilder = AQB::document('users', 'john')->get();
        self::assertEquals('DOCUMENT(users, "john")', $queryBuilder->query);

        $queryBuilder = AQB::document('users', ['users/john', 'users/amy'])->get();
        self::assertEquals('DOCUMENT(users, ["users/john","users/amy"])', $queryBuilder->query);

        $queryBuilder = AQB::document('users', ['john', 'amy'])->get();
        self::assertEquals('DOCUMENT(users, ["john","amy"])', $queryBuilder->query);

        $queryBuilder = AQB::document('users/john')->get();
        self::assertEquals('DOCUMENT("users/john")', $queryBuilder->query);

        $queryBuilder = AQB::document(['users/john', 'users/amy'])->get();
        self::assertEquals('DOCUMENT(["users/john","users/amy"])', $queryBuilder->query);
    }
}