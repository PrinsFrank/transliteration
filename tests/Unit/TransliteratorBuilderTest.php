<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Scripts\ScriptAlias;
use PrinsFrank\Transliteration\ConversionSet\Replace;
use PrinsFrank\Transliteration\Syntax\Enum\SpecialTag;
use PrinsFrank\Transliteration\Syntax\Enum\TransliterationDirection;
use PrinsFrank\Transliteration\Exception\UnableToCreateTransliteratorException;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\BasicID;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Filter;
use PrinsFrank\Transliteration\Syntax\FormalId\CompoundID;
use PrinsFrank\Transliteration\Syntax\FormalId\SingleID;
use PrinsFrank\Transliteration\Syntax\Rule\Components\Conversion;
use PrinsFrank\Transliteration\Syntax\Rule\Components\VariableDefinition;
use PrinsFrank\Transliteration\Syntax\Rule\RuleList;
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
     * @covers ::__construct
     * @covers ::applyConversionSet
     */
    public function testApplyConversionSet(): void
    {
        static::assertSame('bar', (new TransliteratorBuilder())->applyConversionSet(new Replace('foo', 'bar'))->transliterate('foo'));
    }

    /**
     * @covers ::__construct
     * @covers ::applyConversionSets
     */
    public function testApplyConversionSets(): void
    {
        static::assertSame('bar', (new TransliteratorBuilder())->applyConversionSets([new Replace('foo', 'bar')])->transliterate('foo'));
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
