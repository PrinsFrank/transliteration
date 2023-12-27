<?php
declare(strict_types=1);

namespace FormalIdSyntax;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Country\CountryAlpha2;
use PrinsFrank\Standards\Language\LanguageAlpha2;
use PrinsFrank\Standards\LanguageTag\LanguageTag;
use PrinsFrank\Standards\Scripts\ScriptName;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\BasicID;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\SpecialTag;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Variant;

/** @coversDefaultClass \PrinsFrank\TransliteratorWrapper\FormalIdSyntax\BasicID */
class BasicIdTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::__toString
     *
     * @source examples from https://unicode-org.github.io/icu/userguide/transforms/general/#basic-ids
     */
    public function testToString(): void
    {
        static::assertSame('Katakana-Latin', (new BasicID(ScriptName::Katakana, ScriptName::Latin))->__toString());
        static::assertSame('Null', (new BasicID(null, SpecialTag::Null))->__toString());
        static::assertSame('Hex-Any/Perl', (new BasicID(SpecialTag::Hex, SpecialTag::Any, Variant::Perl))->__toString());
        static::assertSame('Latin-el', (new BasicID(ScriptName::Latin, new LanguageTag(LanguageAlpha2::Greek_Modern_1453)))->__toString());
        static::assertSame('Greek-en_US/UNGEGN', (new BasicID(ScriptName::Greek, new LanguageTag(LanguageAlpha2::English, regionSubtag: CountryAlpha2::United_States_of_America), Variant::UNGEGN))->__toString());
        static::assertSame('Any-Hex/Unicode', (new BasicID(SpecialTag::Any, SpecialTag::Hex, Variant::Unicode))->__toString());
        static::assertSame('Any-Hex/Perl', (new BasicID(SpecialTag::Any, SpecialTag::Hex, Variant::Perl))->__toString());
        static::assertSame('Any-Hex/XML', (new BasicID(SpecialTag::Any, SpecialTag::Hex, Variant::XML))->__toString());
    }
}
