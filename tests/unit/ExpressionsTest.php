<?php

use LaravelFreelancerNL\FluentAQL\Expressions\ListExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\StringExpression;
use LaravelFreelancerNL\FluentAQL\Facades\AQB;

/**
 * Class StructureTest.
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses
 */
class ExpressionsTest extends TestCase
{
    /**
     * list expression returns proper json formatted list.
     * @test
     */
    public function ListExpression()
    {
        $expression = new ListExpression([1, 2, '"You know nothing John Snow"']);
        self::assertEquals('[1,2,"You know nothing John Snow"]', (string) $expression);

        $expression = new ListExpression(['"users/john"', '"users/amy"']);
        self::assertEquals('["users/john","users/amy"]', (string) $expression);
    }

    /**
     * string expression returns proper json encoded string.
     * @test
     */
    public function StringExpression()
    {
        $expression = new StringExpression('You know nothing John Snow');
        self::assertEquals('"You know nothing John Snow"', (string) $expression);

        $expression = new StringExpression('You know\ nothing John Snow');
        self::assertEquals('"You know\\\ nothing John Snow"', (string) $expression);

        $expression = new StringExpression('users/JohnSnow');
        self::assertEquals('"users/JohnSnow"', (string) $expression);

        $expression = new StringExpression('and Ned Stark said: "Winter is coming"');
        self::assertEquals('"and Ned Stark said: \"Winter is coming\""', (string) $expression);
    }
}
