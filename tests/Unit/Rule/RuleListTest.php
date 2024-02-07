<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\Rule;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\Enum\SpecialTag;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\BasicID;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Character;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Filter;
use PrinsFrank\Transliteration\FormalIdSyntax\SingleID;
use PrinsFrank\Transliteration\Rule\Components\Conversion;
use PrinsFrank\Transliteration\Rule\RuleList;

/** @coversDefaultClass \PrinsFrank\Transliteration\Rule\RuleList */
class RuleListTest extends TestCase
{
    /** @covers ::__construct */
    public function testConstructThrowsExceptionWhenRulesIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Param $rules should be a non-empty array');
        /** @phpstan-ignore-next-line */
        new RuleList([], null, null);
    }

    /** @covers ::__construct */
    public function testConstructThrowsExceptionWhenRulesIsOfInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Param $rules should be an array of "PrinsFrank\Transliteration\FormalIdSyntax\SingleID|PrinsFrank\Transliteration\Rule\Components\Conversion|PrinsFrank\Transliteration\Rule\Components\VariableDefinition"');
        /** @phpstan-ignore-next-line */
        new RuleList([null], null, null);
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     */
    public function testToString(): void
    {
        static::assertSame(
            <<<EOD
            ::Any;

            EOD,
            (new RuleList([new SingleID(new BasicID(SpecialTag::Any))]))->__toString()
        );
        static::assertSame(
            <<<EOD
            foo>bar;

            EOD,
            (new RuleList([new Conversion('foo', 'bar')]))->__toString()
        );
        static::assertSame(
            <<<EOD
            [a-z];
            foo>bar;

            EOD,
            (new RuleList([new Conversion('foo', 'bar')], (new Filter())->addRange(new Character('a'), new Character('z'))))->__toString()
        );
        static::assertSame(
            <<<EOD
            [a-z];
            foo>bar;
            [A-Z];

            EOD,
            (new RuleList([new Conversion('foo', 'bar')], (new Filter())->addRange(new Character('a'), new Character('z')), (new Filter())->addRange(new Character('A'), new Character('Z'))))->__toString()
        );
    }
}
