<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Scripts\ScriptAlias;
use PrinsFrank\Transliteration\Enum\SpecialTag;
use PrinsFrank\Transliteration\Enum\TransliterationDirection;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\BasicID;
use PrinsFrank\Transliteration\FormalIdSyntax\SingleID;
use PrinsFrank\Transliteration\Rule\Components\Conversion;
use PrinsFrank\Transliteration\Rule\RuleList;
use PrinsFrank\Transliteration\TypedTransliterator;
use Transliterator;

/** @coversDefaultClass \PrinsFrank\Transliteration\TypedTransliterator */
class TypedTransliteratorTest extends TestCase
{
    /** @covers ::create */
    public function testCreate(): void
    {
        static::assertEquals(
            Transliterator::create('Any-Latin;'),
            (new TypedTransliterator())->create(new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any)))
        );
        static::assertEquals(
            Transliterator::create('Any-Latin;', Transliterator::REVERSE),
            (new TypedTransliterator())->create(new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any)), TransliterationDirection::REVERSE)
        );
        static::assertEquals(
            Transliterator::createFromRules('::Any-Latin;'),
            (new TypedTransliterator())->create(new RuleList([new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any))]))
        );
        static::assertEquals(
            Transliterator::createFromRules('::Any-Latin;', Transliterator::REVERSE),

            (new TypedTransliterator())->create(new RuleList([new SingleID(new BasicID(ScriptAlias::Latin, SpecialTag::Any))]), TransliterationDirection::REVERSE)
        );
    }

    /** @covers ::transliterate */
    public function testTransliterate(): void
    {
        static::assertSame('bfoorbfoor', (new TypedTransliterator())->transliterate('foobar', new RuleList([new Conversion('foo', 'bar'), new SingleID(new BasicID(SpecialTag::Null)), new Conversion('a', 'foo')])));
    }

    /** @covers ::listIDs */
    public function testListIDs(): void
    {
        static::assertNotEmpty((new TypedTransliterator())->listIDs());
    }
}
