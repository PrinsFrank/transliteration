<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\Syntax\Rule\Components;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\Syntax\Enum\TransliterationDirection;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use PrinsFrank\Transliteration\Syntax\Rule\Components\Conversion;

/** @coversDefaultClass \PrinsFrank\Transliteration\Syntax\Rule\Components\Conversion */
class ConversionTest extends TestCase
{
    /** @covers ::__construct */
    public function testConstructThrowsExceptionWhenEmptyStringIsSuppliedForTextToReplace(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$textToReplace should be a non-empty string');
        new Conversion('', '');
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     */
    public function testToString(): void
    {
        static::assertSame(
            'a>b',
            (new Conversion('a', 'b'))->__toString()
        );
        static::assertSame(
            'a>b',
            (new Conversion('a', 'b', conversionDirection: TransliterationDirection::FORWARD))->__toString()
        );
        static::assertSame(
            'b<a',
            (new Conversion('a', 'b', conversionDirection: TransliterationDirection::REVERSE))->__toString()
        );
        static::assertSame(
            'x{a}y>b|z',
            (new Conversion('a', 'b', 'x', 'y', 'z', TransliterationDirection::FORWARD))->__toString()
        );
        static::assertSame(
            'b|z<x{a}y',
            (new Conversion('a', 'b', 'x', 'y', 'z', TransliterationDirection::REVERSE))->__toString()
        );
    }
}
