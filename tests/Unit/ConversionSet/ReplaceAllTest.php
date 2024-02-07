<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\ConversionSet;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\ConversionSet\ReplaceAll;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use PrinsFrank\Transliteration\Syntax\Rule\Components\Conversion;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @coversDefaultClass \PrinsFrank\Transliteration\ConversionSet\ReplaceAll */
class ReplaceAllTest extends TestCase
{
    /** @covers ::__construct */
    public function testConstructThrowsExceptionOnEmptyArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('param $strings should be a non-empty array');
        /** @phpstan-ignore-next-line */
        new ReplaceAll([], '');
    }

    /**
     * @covers ::__construct
     * @covers ::apply
     */
    public function testApply(): void
    {
        static::assertEquals(
            [
                new Conversion('a', 'b')
            ],
            (new TransliteratorBuilder())
                ->applyConversionSet(new ReplaceAll(['a'], 'b'))
                ->getConversions()
        );
        static::assertEquals(
            [
                new Conversion('a', 'b'),
                new Conversion('c', 'b'),
            ],
            (new TransliteratorBuilder())
                ->applyConversionSet(new ReplaceAll(['a', 'c'], 'b'))
                ->getConversions()
        );
    }
}
