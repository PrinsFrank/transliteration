<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\ConversionSet;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\ConversionSet\Replace;
use PrinsFrank\Transliteration\Syntax\Rule\Components\Conversion;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @coversDefaultClass \PrinsFrank\Transliteration\ConversionSet\Replace */
class ReplaceTest extends TestCase
{
    /** @covers ::apply */
    public function testApply(): void
    {
        static::assertEquals(
            [
                new Conversion('a', 'b')
            ],
            (new TransliteratorBuilder())
                ->applyConversionSet(new Replace('a', 'b'))
                ->getConversions()
        );
    }
}
