<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\Syntax\FormalId\Components;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Character;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Filter;

/** @coversDefaultClass \PrinsFrank\Transliteration\Syntax\FormalId\Components\Filter */
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

    /** @covers ::__toString */
    public function testInverse(): void
    {
        static::assertSame('[^a]', (new Filter(true))->addChar(new Character('a'))->__toString());
        static::assertSame('[^a-z]', (new Filter(true))->addRange(new Character('a'), new Character('z'))->__toString());
    }

    /** @covers ::__toString */
    public function testToStringOnEmptyFilter(): void
    {
        static::assertSame('', (new Filter())->__toString());
        static::assertSame('', (new Filter(true))->__toString());
    }

    /**
     * @covers ::__construct
     * @covers ::getInverse
     */
    public function testGetInverse(): void
    {
        static::assertEquals(new Filter(true), (new Filter())->getInverse());
        static::assertEquals(new Filter(true), (new Filter(false))->getInverse());
        static::assertEquals(new Filter(false), (new Filter(true))->getInverse());

        static::assertEquals(new Filter(), (new Filter())->getInverse()->getInverse());
        static::assertEquals(new Filter(false), (new Filter(false))->getInverse()->getInverse());
        static::assertEquals(new Filter(true), (new Filter(true))->getInverse()->getInverse());

        static::assertEquals(
            (new Filter())->addChar(new Character('a'))->addRange(new Character('A'), new Character('Z')),
            (new Filter())->addChar(new Character('a'))->addRange(new Character('A'), new Character('Z'))->getInverse()->getInverse(),
        );
    }
}
