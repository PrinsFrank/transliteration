<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\ConversionSet;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Scripts\ScriptAlias;
use PrinsFrank\Transliteration\ConversionSet\ConvertScriptLanguage;
use PrinsFrank\Transliteration\Syntax\Enum\SpecialTag;
use PrinsFrank\Transliteration\Syntax\Enum\Variant;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\BasicID;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Character;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Filter;
use PrinsFrank\Transliteration\Syntax\FormalId\SingleID;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @coversDefaultClass \PrinsFrank\Transliteration\ConversionSet\ConvertScriptLanguage */
class ConvertScriptLanguageTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::apply
     */
    public function testApply(): void
    {
        static::assertEquals(
            [
                new SingleID(new BasicID(SpecialTag::Any, ScriptAlias::Latin))
            ],
            (new TransliteratorBuilder())
                ->applyConversionSet(new ConvertScriptLanguage(ScriptAlias::Latin, SpecialTag::Any))
                ->getConversions()
        );
        static::assertEquals(
            [
                new SingleID(new BasicID(SpecialTag::Any, ScriptAlias::Latin, Variant::Unicode))
            ],
            (new TransliteratorBuilder())
                ->applyConversionSet(new ConvertScriptLanguage(ScriptAlias::Latin, SpecialTag::Any, Variant::Unicode))
                ->getConversions()
        );
        static::assertEquals(
            [
                new SingleID(new BasicID(SpecialTag::Any, ScriptAlias::Latin, Variant::Unicode), (new Filter())->addChar(new Character('a')))
            ],
            (new TransliteratorBuilder())
                ->applyConversionSet(new ConvertScriptLanguage(ScriptAlias::Latin, SpecialTag::Any, Variant::Unicode, (new Filter())->addChar(new Character('a'))))
                ->getConversions()
        );
    }
}
