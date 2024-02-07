<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\ConversionSet;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\ConversionSet\IPAToEnglishApproximation;
use PrinsFrank\Transliteration\Syntax\Rule\Components\Conversion;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @coversDefaultClass \PrinsFrank\Transliteration\ConversionSet\IPAToEnglishApproximation */
class IPAToEnglishApproximationTest extends TestCase
{
    /** @covers ::apply */
    public function testApply(): void
    {
        static::assertEquals(
            [
                new Conversion('dʒ', 'g'),
                new Conversion('kʰ', 'c'),
                new Conversion('kʷ', 'qu'),
                new Conversion('kᶣ', 'cu'),
                new Conversion('ɫ', 'll'),
                new Conversion('ŋ', 'n'),
                new Conversion('Ŋ', 'N'),
                new Conversion('ɲ', 'n'),
                new Conversion('Ɲ', 'N'),
                new Conversion('pʰ', 'p'),
                new Conversion('ʃ', 'sh'),
                new Conversion('Ʃ', 'SH'),
                new Conversion('tʰ', 't'),
                new Conversion('tʃ', 'ch'),
                new Conversion('aː', 'a'),
                new Conversion('Aː', 'A'),
                new Conversion('ɛ', 'e'),
                new Conversion('Ɛ', 'E'),
                new Conversion('eː', 'a'),
                new Conversion('Eː', 'A'),
                new Conversion('ɪ', 'i'),
                new Conversion('Ɪ', 'I'),
                new Conversion('iː', 'i'),
                new Conversion('Iː', 'I'),
                new Conversion('ɔ', 'o'),
                new Conversion('Ɔ', 'O'),
                new Conversion('oː', 'aw'),
                new Conversion('ʊ', 'u'),
                new Conversion('Ʊ', 'U'),
                new Conversion('ʌ', 'u'),
                new Conversion('Ʌ', 'U'),
                new Conversion('uː', 'u'),
                new Conversion('yː', 'u'),
                new Conversion('ae̯', 'igh'),
                new Conversion('oe̯', 'oy'),
                new Conversion('au̯', 'ow'),
                new Conversion('ei̯', 'ay'),
                new Conversion('ui̯', 'ui'),
            ],
            (new TransliteratorBuilder())
                ->applyConversionSet(new IPAToEnglishApproximation())
                ->getConversions()
        );
    }
}
