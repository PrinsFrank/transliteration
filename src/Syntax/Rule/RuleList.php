<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Syntax\Rule;

use PrinsFrank\Transliteration\Syntax\Enum\Literal;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Filter;
use PrinsFrank\Transliteration\Syntax\FormalId\SingleID;
use PrinsFrank\Transliteration\Syntax\Rule\Components\Conversion;
use PrinsFrank\Transliteration\Syntax\Rule\Components\VariableDefinition;
use Stringable;

/**
 * @internal
 *
 * @see https://unicode-org.github.io/icu/userguide/transforms/general/#rule-syntax
 */
final class RuleList implements Stringable
{
    /**
     * @param non-empty-array<SingleID|Conversion|VariableDefinition> $rules
     * @throws InvalidArgumentException
     */
    public function __construct(
        public readonly array $rules,
        public readonly Filter|null $globalFilter = null,
        public readonly Filter|null $inverseFilter = null,
    ) {
        if ($rules === []) {
            throw new InvalidArgumentException('Param $rules should be a non-empty array');
        }

        foreach ($this->rules as $rule) {
            $rule instanceof SingleID === true || $rule instanceof Conversion === true || $rule instanceof VariableDefinition === true || throw new InvalidArgumentException('Param $rules should be an array of "' . SingleID::class . '|' . Conversion::class . '|' . VariableDefinition::class . '"');
        }
    }

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
