<?php

declare(strict_types=1);

namespace Tests\Unit;

use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class StructureTest.
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Helpers
 */
class HelpersTest extends TestCase
{
    public function testValidateBindParameterSyntax()
    {
        self::assertTrue(isStringable('some string'));
        self::assertTrue(isStringable(1));
        self::assertTrue(isStringable(null));
        self::assertTrue(isStringable((new StringableClass(2))));
        self::assertFalse(isStringable([1, 2, 3]));
        self::assertFalse(isStringable((object) [1, 2, 3]));
    }
}

class StringableClass
{
    public int $foo;

    public function __construct(int $foo)
    {
        $this->foo = $foo;
    }

    public function __toString(): string
    {
        return (string) $this->foo;
    }
}
