<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\Rule;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
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
}
