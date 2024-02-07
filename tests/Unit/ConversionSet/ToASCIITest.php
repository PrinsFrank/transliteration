<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\ConversionSet;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Scripts\ScriptName;
use PrinsFrank\Transliteration\ConversionSet\ToASCII;
use PrinsFrank\Transliteration\Syntax\Enum\SpecialTag;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\BasicID;
use PrinsFrank\Transliteration\Syntax\FormalId\SingleID;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @coversDefaultClass \PrinsFrank\Transliteration\ConversionSet\ToASCII */
class ToASCIITest extends TestCase
{
    /** @covers ::apply */
    public function testApply(): void
    {
        static::assertEquals(
            [
                new SingleID(new BasicID(ScriptName::Latin, SpecialTag::Any)),
                new SingleID(new BasicID(SpecialTag::ASCII, ScriptName::Latin))
            ],
            (new TransliteratorBuilder())
                ->applyConversionSet(new ToASCII())
                ->getConversions()
        );
    }
}
