<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\Syntax\FormalId\Components;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Character;

/** @coversDefaultClass \PrinsFrank\Transliteration\Syntax\FormalId\Components\Character */
class CharacterTest extends TestCase
{
    /** @covers ::__construct */
    public function testConstructThrowsExceptionOnEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$char should be of length 1');
        new Character('');
    }

    /** @covers ::__construct */
    public function testConstructThrowsExceptionOnMultiCharacterString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$char should be of length 1');
        new Character('ab');
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::escape
     */
    public function testToString(): void
    {
        static::assertSame('a', (string) new Character('a'));
        static::assertSame('\u003b', (string) new Character(';'));
    }

    /** @covers ::escape */
    public function testEscape(): void
    {
        static::assertSame(null, Character::escape(null));
        static::assertSame('a', Character::escape('a'));
        static::assertSame('\u003b', Character::escape(';'));
        static::assertSame('\u003ba\u003b', Character::escape(';a;'));
    }
}
