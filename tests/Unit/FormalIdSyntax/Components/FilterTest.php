<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\FormalIdSyntax\Components;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Character;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Filter;

/** @coversDefaultClass \PrinsFrank\Transliteration\FormalIdSyntax\Components\Filter */
class FilterTest extends TestCase
{
    /**
     * @covers ::addChar
     * @covers ::__toString
     */
    public function testAddChar(): void
    {
        static::assertSame('[a]', (new Filter())->addChar(new Character('a'))->__toString());
        static::assertSame('[aA]', (new Filter())->addChar(new Character('a'))->addChar(new Character('A'))->__toString());
    }

    /**
     * @covers ::addRange
     * @covers ::__toString
     */
    public function testAddRange(): void
    {
        static::assertSame('[a-z]', (new Filter())->addRange(new Character('a'), new Character('z'))->__toString());
        static::assertSame('[a-zA-Z]', (new Filter())->addRange(new Character('a'), new Character('z'))->addRange(new Character('A'), new Character('Z'))->__toString());
    }

    /**
     * @covers ::inverse
     * @covers ::__toString
     */
    public function testInverse(): void
    {
        static::assertSame('[^a]', (new Filter())->addChar(new Character('a'))->inverse()->__toString());
        static::assertSame('[^a-z]', (new Filter())->addRange(new Character('a'), new Character('z'))->inverse()->__toString());
    }

    /** @covers ::__toString */
    public function testToStringOnEmptyFilter(): void
    {
        static::assertSame('', (new Filter())->__toString());
        static::assertSame('', (new Filter())->inverse()->__toString());
    }
}
