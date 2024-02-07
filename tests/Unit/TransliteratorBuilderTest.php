<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Scripts\ScriptAlias;
use PrinsFrank\Standards\Scripts\ScriptName;
use PrinsFrank\Transliteration\Enum\SpecialTag;
use PrinsFrank\Transliteration\Enum\TransliterationDirection;
use PrinsFrank\Transliteration\Enum\Variant;
use PrinsFrank\Transliteration\Exception\UnableToCreateTransliteratorException;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\BasicID;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Character;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Filter;
use PrinsFrank\Transliteration\FormalIdSyntax\CompoundID;
use PrinsFrank\Transliteration\FormalIdSyntax\SingleID;
use PrinsFrank\Transliteration\Rule\Components\Conversion;
use PrinsFrank\Transliteration\Rule\Components\VariableDefinition;
use PrinsFrank\Transliteration\Rule\RuleList;
use PrinsFrank\Transliteration\TransliteratorBuilder;
use PrinsFrank\Transliteration\Transliterator\TypedTransliterator;

/** @coversDefaultClass \PrinsFrank\Transliteration\TransliteratorBuilder */
class TransliteratorBuilderTest extends TestCase
{
    /** @covers ::getDirection */
    public function testGetDirectionWithDefault(): void
    {
        static::assertSame(TransliterationDirection::FORWARD, (new TransliteratorBuilder())->getDirection());
    }

    /**
     * @covers ::setDirection
     * @covers ::getDirection
     */
    public function testSetDirection(): void
    {
        static::assertSame(
            TransliterationDirection::REVERSE,
            (new TransliteratorBuilder())->setDirection(TransliterationDirection::REVERSE)->getDirection()
        );
    }

    /** @covers ::getGlobalFilter */
    public function testGetGlobalFilterWithNull(): void
    {
        static::assertNull((new TransliteratorBuilder())->getGlobalFilter());
    }

    /**
     * @covers ::setGlobalFilter
     * @covers ::getGlobalFilter
     */
    public function testSetGlobalFilter(): void
    {
        $globalFilter = new Filter();
        static::assertSame($globalFilter, (new TransliteratorBuilder())->setGlobalFilter($globalFilter)->getGlobalFilter());
    }

    /**
     * @covers ::addSingleID
     * @covers ::getConversions
     */
    public function testAddSingleID(): void
    {
        $singleID1 = new SingleID(new BasicID(SpecialTag::Any));
        static::assertSame(
            [
                $singleID1,
            ],
            (new TransliteratorBuilder())
                ->addSingleID($singleID1)
                ->getConversions()
        );

        $singleID2 = new SingleID(new BasicID(SpecialTag::Any));
        static::assertSame(
            [
                $singleID1,
                $singleID2,
            ],
            (new TransliteratorBuilder())
                ->addSingleID($singleID1)
                ->addSingleID($singleID2)
                ->getConversions()
        );
    }

    /**
     * @covers ::addConversion
     * @covers ::getConversions
     */
    public function testAddConversion(): void
    {
        $conversion1 = new Conversion('a', 'b');
        static::assertSame(
            [
                $conversion1,
            ],
            (new TransliteratorBuilder())
                ->addConversion($conversion1)
                ->getConversions()
        );

        $conversion2 = new Conversion('b', 'c');
        static::assertSame(
            [
                $conversion1,
                $conversion2,
            ],
            (new TransliteratorBuilder())
                ->addConversion($conversion1)
                ->addConversion($conversion2)
                ->getConversions()
        );
    }

    /**
     * @covers ::addVariableDefinition
     * @covers ::getConversions
     */
    public function testAddVariableDefinition(): void
    {
        $variableDefinition1 = new VariableDefinition('foo', 'bar');
        static::assertSame(
            [
                $variableDefinition1,
            ],
            (new TransliteratorBuilder())
                ->addVariableDefinition($variableDefinition1)
                ->getConversions()
        );

        $variableDefinition2 = new VariableDefinition('bar', 'bop');
        static::assertSame(
            [
                $variableDefinition1,
                $variableDefinition2,
            ],
            (new TransliteratorBuilder())
                ->addVariableDefinition($variableDefinition1)
                ->addVariableDefinition($variableDefinition2)
                ->getConversions()
        );
    }

    /**
     * @covers ::convertScriptLanguage
     * @covers ::getConversions
     */
    public function testConvertScriptLanguage(): void
    {
        static::assertEquals(
            [
                new SingleID(new BasicID(SpecialTag::Any, ScriptAlias::Latin))
            ],
            (new TransliteratorBuilder())
                ->convertScriptLanguage(ScriptAlias::Latin, SpecialTag::Any)
                ->getConversions()
        );
        static::assertEquals(
            [
                new SingleID(new BasicID(SpecialTag::Any, ScriptAlias::Latin, Variant::Unicode))
            ],
            (new TransliteratorBuilder())
                ->convertScriptLanguage(ScriptAlias::Latin, SpecialTag::Any, Variant::Unicode)
                ->getConversions()
        );
        static::assertEquals(
            [
                new SingleID(new BasicID(SpecialTag::Any, ScriptAlias::Latin, Variant::Unicode), (new Filter())->addChar(new Character('a')))
            ],
            (new TransliteratorBuilder())
                ->convertScriptLanguage(ScriptAlias::Latin, SpecialTag::Any, Variant::Unicode, (new Filter())->addChar(new Character('a')))
                ->getConversions()
        );
    }

    /**
     * @covers ::convertToScriptLanguage
     * @covers ::getConversions
     */
    public function testConvertToScriptLanguage(): void
    {
        static::assertEquals(
            [
                new SingleID(new BasicID(SpecialTag::Any))
            ],
            (new TransliteratorBuilder())
                ->convertToScriptLanguage(SpecialTag::Any)
                ->getConversions()
        );
        static::assertEquals(
            [
                new SingleID(new BasicID(SpecialTag::Any, variant: Variant::Unicode))
            ],
            (new TransliteratorBuilder())
                ->convertToScriptLanguage(SpecialTag::Any, Variant::Unicode)
                ->getConversions()
        );
        static::assertEquals(
            [
                new SingleID(new BasicID(SpecialTag::Any, variant: Variant::Unicode), (new Filter())->addChar(new Character('a')))
            ],
            (new TransliteratorBuilder())
                ->convertToScriptLanguage(SpecialTag::Any, Variant::Unicode, (new Filter())->addChar(new Character('a')))
                ->getConversions()
        );
    }

    /**
     * @covers ::toASCII
     * @covers ::getConversions
     */
    public function testToASCII(): void
    {
        static::assertEquals(
            [
                new SingleID(new BasicID(ScriptName::Latin, SpecialTag::Any)),
                new SingleID(new BasicID(SpecialTag::ASCII, ScriptName::Latin))
            ],
            (new TransliteratorBuilder())
                ->toASCII()
                ->getConversions()
        );
    }

    /**
     * @covers ::toXSampa
     * @covers ::getConversions
     */
    public function testToXSampa(): void
    {
        static::assertEquals(
            [
                new SingleID(new BasicID(SpecialTag::XSampa, SpecialTag::IPA))
            ],
            (new TransliteratorBuilder())
                ->toXSampa()
                ->getConversions()
        );
    }

    /**
     * @covers ::toIPA
     * @covers ::getConversions
     */
    public function testToIPA(): void
    {
        static::assertEquals(
            [
                new SingleID(new BasicID(SpecialTag::IPA, SpecialTag::XSampa))
            ],
            (new TransliteratorBuilder())
                ->toIPA()
                ->getConversions()
        );
    }

    /**
     * @covers ::remove
     * @covers ::getConversions
     */
    public function testRemove(): void
    {
        static::assertEquals(
            [
                new SingleID(new BasicID(SpecialTag::Remove), (new Filter())->addChar(new Character('a')))
            ],
            (new TransliteratorBuilder())
                ->remove((new Filter())->addChar(new Character('a')))
                ->getConversions()
        );
    }

    /**
     * @covers ::keep
     * @covers ::getConversions
     */
    public function testKeep(): void
    {
        static::assertEquals(
            [
                new SingleID(new BasicID(SpecialTag::Remove), (new Filter())->addChar(new Character('a')))
            ],
            (new TransliteratorBuilder())
                ->keep((new Filter(true))->addChar(new Character('a')))
                ->getConversions()
        );
    }

    /**
     * @covers ::replace
     * @covers ::getConversions
     */
    public function testReplace(): void
    {
        static::assertEquals(
            [
                new Conversion('a', 'b')
            ],
            (new TransliteratorBuilder())
                ->replace('a', 'b')
                ->getConversions()
        );
    }

    /**
     * @covers ::IPAToEnglishApproximation
     * @covers ::getConversions
     */
    public function testIPAToEnglishApproximation(): void
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
                ->IPAToEnglishApproximation()
                ->getConversions()
        );
    }

    /** @covers ::getTransliterator */
    public function testGetTransliteratorThrowsExceptionWhenNoConversions(): void
    {
        $this->expectException(UnableToCreateTransliteratorException::class);
        $this->expectExceptionMessage('There are no conversions');
        (new TransliteratorBuilder())->getTransliterator();
    }

    /**
     * @covers ::getTransliterator
     * @covers ::containsRuleSyntax
     */
    public function testGetTransliteratorWithRuleSyntax(): void
    {
        static::assertEquals(
            TypedTransliterator::create(new RuleList([new Conversion('a', 'b')])),
            (new TransliteratorBuilder())->addConversion(new Conversion('a', 'b'))->getTransliterator()
        );
        static::assertEquals(
            TypedTransliterator::create(new RuleList([new Conversion('a', 'b')]), TransliterationDirection::REVERSE),
            (new TransliteratorBuilder())->setDirection(TransliterationDirection::REVERSE)->addConversion(new Conversion('a', 'b'))->getTransliterator()
        );
    }

    /**
     * @covers ::getTransliterator
     * @covers ::containsRuleSyntax
     */
    public function testGetTransliteratorWithSingleID(): void
    {
        static::assertEquals(
            TypedTransliterator::create(new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any))),
            (new TransliteratorBuilder())->addSingleID(new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any)))->getTransliterator()
        );
        static::assertEquals(
            TypedTransliterator::create(new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any)), TransliterationDirection::REVERSE),
            (new TransliteratorBuilder())->setDirection(TransliterationDirection::REVERSE)->addSingleID(new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any)))->getTransliterator()
        );
    }

    /**
     * @covers ::getTransliterator
     * @covers ::containsRuleSyntax
     */
    public function testGetTransliteratorWithCompoundID(): void
    {
        static::assertEquals(
            TypedTransliterator::create(new CompoundID([new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any)), new SingleID(new BasicID(SpecialTag::Hex))])),
            (new TransliteratorBuilder())->addSingleID(new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any)))->addSingleID(new SingleID(new BasicID(SpecialTag::Hex)))->getTransliterator()
        );
        static::assertEquals(
            TypedTransliterator::create(new CompoundID([new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any)), new SingleID(new BasicID(SpecialTag::Hex))]), TransliterationDirection::REVERSE),
            (new TransliteratorBuilder())->setDirection(TransliterationDirection::REVERSE)->addSingleID(new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any)))->addSingleID(new SingleID(new BasicID(SpecialTag::Hex)))->getTransliterator()
        );
    }

    /** @covers ::transliterate */
    public function testTransliterateThrowsExceptionWhenNoConversions(): void
    {
        $this->expectException(UnableToCreateTransliteratorException::class);
        $this->expectExceptionMessage('There are no conversions');
        (new TransliteratorBuilder())->transliterate('');
    }

    /**
     * @covers ::transliterate
     * @covers ::containsRuleSyntax
     */
    public function testTransliterateWithRuleSyntax(): void
    {
        static::assertEquals(
            'b',
            (new TransliteratorBuilder())->addConversion(new Conversion('a', 'b'))->transliterate('a')
        );
        static::assertEquals(
            'a',
            (new TransliteratorBuilder())->setDirection(TransliterationDirection::REVERSE)->addConversion(new Conversion('a', 'b'))->transliterate('a')
        );
    }

    /**
     * @covers ::transliterate
     * @covers ::containsRuleSyntax
     */
    public function testTransliterateWithSingleID(): void
    {
        static::assertEquals(
            'a',
            (new TransliteratorBuilder())->addSingleID(new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any)))->transliterate('ア')
        );
        static::assertEquals(
            'ア',
            (new TransliteratorBuilder())->setDirection(TransliterationDirection::REVERSE)->addSingleID(new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any)))->transliterate('ア')
        );
    }

    /**
     * @covers ::transliterate
     * @covers ::containsRuleSyntax
     */
    public function testTransliterateWithCompoundID(): void
    {
        static::assertEquals(
            '\u0061',
            (new TransliteratorBuilder())->addSingleID(new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any)))->addSingleID(new SingleID(new BasicID(SpecialTag::Hex)))->transliterate('a')
        );
        static::assertEquals(
            'b',
            (new TransliteratorBuilder())->setDirection(TransliterationDirection::REVERSE)->addSingleID(new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any)))->addSingleID(new SingleID(new BasicID(SpecialTag::Hex)))->transliterate('\u0062')
        );
    }
}
