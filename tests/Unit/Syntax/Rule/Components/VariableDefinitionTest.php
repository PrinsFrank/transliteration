<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\Syntax\Rule\Components;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use PrinsFrank\Transliteration\Syntax\Rule\Components\VariableDefinition;

/** @coversDefaultClass \PrinsFrank\Transliteration\Syntax\Rule\Components\VariableDefinition */
class VariableDefinitionTest extends TestCase
{
    /** @covers ::__construct */
    public function testToStringThrowsExceptionWhenNameIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A variable cannot have an empty name or value');
        new VariableDefinition('', 'a');
    }

    /** @covers ::__construct */
    public function testToStringThrowsExceptionWhenValueIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A variable cannot have an empty name or value');
        new VariableDefinition('a', '');
    }

    /** @covers ::__construct */
    public function testToStringThrowsExceptionWhenNameContainsSpecialCharacter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A variable cannot contain any special characters');
        new VariableDefinition('$', 'a');
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     */
    public function testToString(): void
    {
        static::assertSame('$a = foo;', (new VariableDefinition('a', 'foo'))->__toString());
        static::assertSame('$a = \u0024;', (new VariableDefinition('a', '$'))->__toString());
        static::assertSame('$a = \u003b;', (new VariableDefinition('a', ';'))->__toString());
        static::assertSame('$a = \u003b;', (new VariableDefinition('a', ';'))->__toString());
    }
}
