<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit;

use LaravelFreelancerNL\FluentAQL\Expressions\ListExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\StringExpression;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class StructureTest.
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses
 */
class ExpressionsTest extends TestCase
{
    public function testListExpression()
    {
        $expression = new ListExpression([1, 2, '"You know nothing John Snow"']);
        self::assertEquals('[1,2,"You know nothing John Snow"]', (string) $expression);

        $expression = new ListExpression(['"users/john"', '"users/amy"']);
        self::assertEquals('["users/john","users/amy"]', (string) $expression);
    }

    public function testStringExpression()
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
