<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\ConversionSet;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\ConversionSet\XSampaToIPA;
use PrinsFrank\Transliteration\Syntax\Enum\SpecialTag;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\BasicID;
use PrinsFrank\Transliteration\Syntax\FormalId\SingleID;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @coversDefaultClass \PrinsFrank\Transliteration\ConversionSet\XSampaToIPA */
class XSampaToIPATest extends TestCase
{
    /** @covers ::apply */
    public function testApply(): void
    {
        static::assertEquals(
            [
                new SingleID(new BasicID(SpecialTag::IPA, SpecialTag::XSampa))
            ],
            (new TransliteratorBuilder())
                ->applyConversionSet(new XSampaToIPA())
                ->getConversions()
        );
    }
}
