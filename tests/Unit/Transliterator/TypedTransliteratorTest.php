<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\Transliterator;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Scripts\ScriptAlias;
use PrinsFrank\Transliteration\Syntax\Enum\SpecialTag;
use PrinsFrank\Transliteration\Syntax\Enum\TransliterationDirection;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\BasicID;
use PrinsFrank\Transliteration\Syntax\FormalId\SingleID;
use PrinsFrank\Transliteration\Syntax\Rule\Components\Conversion;
use PrinsFrank\Transliteration\Syntax\Rule\RuleList;
use PrinsFrank\Transliteration\Transliterator\TypedTransliterator;
use Transliterator;

/** @coversDefaultClass \PrinsFrank\Transliteration\Transliterator\TypedTransliterator */
class TypedTransliteratorTest extends TestCase
{
    /** @covers ::create */
    public function testCreate(): void
    {
        static::assertEquals(
            Transliterator::create('Any-Latin;'),
            TypedTransliterator::create(new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any)))
        );
        static::assertEquals(
            Transliterator::create('Any-Latin;', Transliterator::REVERSE),
            TypedTransliterator::create(new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any)), TransliterationDirection::REVERSE)
        );
        static::assertEquals(
            Transliterator::createFromRules('::Any-Latin;'),
            TypedTransliterator::create(new RuleList([new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any))]))
        );
        static::assertEquals(
            Transliterator::createFromRules('::Any-Latin;', Transliterator::REVERSE),
            TypedTransliterator::create(new RuleList([new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any))]), TransliterationDirection::REVERSE)
        );
    }

    /** @covers ::transliterate */
    public function testTransliterate(): void
    {
        static::assertSame('bfoorbfoor', TypedTransliterator::transliterate('foobar', new RuleList([new Conversion('foo', 'bar'), new SingleID(new BasicID(SpecialTag::Null)), new Conversion('a', 'foo')])));
    }

    /** @covers ::listIDs */
    public function testListIDs(): void
    {
        static::assertNotEmpty(TypedTransliterator::listIDs());
    }
}
