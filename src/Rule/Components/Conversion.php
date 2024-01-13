<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Rule\Components;

use PrinsFrank\Transliteration\Enum\Literal;
use PrinsFrank\Transliteration\Enum\TransliterationDirection;
use Stringable;

/**
 * @see https://unicode-org.github.io/icu/userguide/transforms/general/#conversion-rules
 */
final class Conversion implements Stringable
{
    public function __construct(
        public readonly string $textToReplace,
        public readonly string $completedResult,
        public readonly string|null $beforeContext = null,
        public readonly string|null $afterContext = null,
        public readonly string|null $resultToRevisit = null,
        public readonly TransliterationDirection $conversionDirection = TransliterationDirection::FORWARD,
    ) {
    }

    public function __toString(): string
    {
        return match ($this->conversionDirection) {
            TransliterationDirection::FORWARD => sprintf(
                '%s%s%s%s%s',
                $this->withSeparatorAfter($this->beforeContext, Literal::Left_Curly_Bracket->value),
                $this->textToReplace,
                $this->withSeparatorBefore($this->afterContext, Literal::Right_Curly_Bracket->value),
                Literal::Greater_Than_Sign->value . $this->completedResult,
                $this->withSeparatorBefore($this->resultToRevisit, Literal::Vertical_Line->value)
            ),
            TransliterationDirection::REVERSE => $this->completedResult . ($this->resultToRevisit !== null ? Literal::Vertical_Line->value : '') . $this->resultToRevisit . Literal::Less_Than_Sign->value . $this->beforeContext . ($this->beforeContext !== null ? Literal::Left_Curly_Bracket->value : '') . $this->textToReplace . ($this->afterContext !== null ? Literal::Right_Curly_Bracket->value : '') . $this->afterContext,
        };
    }

    protected function withSeparatorBefore(string $text, string|null $separator): string
    {
        if ($separator === null) {
            return '';
        }
        
        return $separator . $text;
    }

    protected function withSeparatorAfter(string $text, string|null $separator): string
    {
        if ($separator === null) {
            return '';
        }
        
        return $text . $separator;
    }
}
