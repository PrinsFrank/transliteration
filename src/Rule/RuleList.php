<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Rule;

use PrinsFrank\Transliteration\FormalIdSyntax\Components\BasicID;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Filter;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Literal;
use PrinsFrank\Transliteration\FormalIdSyntax\CompoundID;
use PrinsFrank\Transliteration\FormalIdSyntax\SingleID;
use PrinsFrank\Transliteration\Rule\Components\Conversion;
use PrinsFrank\Transliteration\Rule\Components\VariableDefinition;
use Stringable;

/**
 * @see https://unicode-org.github.io/icu/userguide/transforms/general/#rule-syntax
 */
final class RuleList implements Stringable
{
    /** @param list<SingleID|Conversion|VariableDefinition> $rules */
    public function __construct(
        public readonly Filter|null $globalFilter = null,
        public readonly array $rules,
        public readonly Filter|null $inverseFilter = null,
    ) { }

    public function __toString(): string
    {
        $string = '';
        if ($this->globalFilter !== null) {
            $string .= $this->globalFilter->__toString() . Literal::Semicolon->value . PHP_EOL;
        }

        foreach ($this->rules as $rule) {
            if ($rule instanceof SingleID) {
                $string .= Literal::Colon->value . Literal::Colon->value;
            }

            $string .= $rule->__toString() . Literal::Semicolon->value . PHP_EOL;
        }

        if ($this->inverseFilter !== null) {
            $string .= $this->inverseFilter->__toString() . Literal::Semicolon->value . PHP_EOL;
        }

        return $string;
    }
}
