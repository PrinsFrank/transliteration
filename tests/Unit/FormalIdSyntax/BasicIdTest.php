<?php
declare(strict_types=1);

namespace FormalIdSyntax;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Country\CountryAlpha2;
use PrinsFrank\Standards\Language\LanguageAlpha2;
use PrinsFrank\Standards\LanguageTag\LanguageTag;
use PrinsFrank\Standards\Scripts\ScriptName;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\BasicID;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\SpecialTag;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\Variant;

/** @coversDefaultClass \PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\BasicID */
class BasicIdTest extends TestCase
{
    /**
     * @source examples from https://unicode-org.github.io/icu/userguide/transforms/general/#basic-ids
     *
     * @covers ::__construct
     * @covers ::__toString
     */
    public function testToString(): void
    {
        static::assertSame('Katakana-Latin', (new BasicID(ScriptName::Latin, ScriptName::Katakana))->__toString());
        static::assertSame('Null', (new BasicID( SpecialTag::Null))->__toString());
        static::assertSame('Hex-Any/Perl', (new BasicID(SpecialTag::Any, SpecialTag::Hex, Variant::Perl))->__toString());
        static::assertSame('Latin-el', (new BasicID(new LanguageTag(LanguageAlpha2::Greek_Modern_1453), ScriptName::Latin))->__toString());
        static::assertSame('Greek-en_US/UNGEGN', (new BasicID(new LanguageTag(LanguageAlpha2::English, regionSubtag: CountryAlpha2::United_States_of_America), ScriptName::Greek, Variant::UNGEGN))->__toString());
        static::assertSame('Any-Hex/Unicode', (new BasicID(SpecialTag::Hex, SpecialTag::Any, Variant::Unicode))->__toString());
        static::assertSame('Any-Hex/Perl', (new BasicID(SpecialTag::Hex, SpecialTag::Any, Variant::Perl))->__toString());
        static::assertSame('Any-Hex/XML', (new BasicID(SpecialTag::Hex, SpecialTag::Any, Variant::XML))->__toString());
    }
}
