<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Rule\Components;

use PrinsFrank\Transliteration\Enum\Literal;
use PrinsFrank\Transliteration\Enum\TransliterationDirection;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Character;
use Stringable;

/**
 * @api
 * @see https://unicode-org.github.io/icu/userguide/transforms/general/#conversion-rules
 */
final class Conversion implements Stringable
{
    /**
     * @phpstan-assert non-empty-string $textToReplace
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        public readonly string $textToReplace,
        public readonly string $completedResult,
        public readonly string|null $beforeContext = null,
        public readonly string|null $afterContext = null,
        public readonly string|null $resultToRevisit = null,
        public readonly TransliterationDirection $conversionDirection = TransliterationDirection::FORWARD,
    ) {
        if ($this->textToReplace === '') {
            throw new InvalidArgumentException('$textToReplace should be a non-empty string');
        }
    }

    public function __toString(): string
    {
        return match ($this->conversionDirection) {
            TransliterationDirection::FORWARD => Character::escape($this->beforeContext) . ($this->beforeContext !== null ? Literal::Left_Curly_Bracket->value : '')
                . Character::escape($this->textToReplace)
                . ($this->afterContext !== null ? Literal::Right_Curly_Bracket->value : '') . Character::escape($this->afterContext)
                . Literal::Greater_Than_Sign->value
                . Character::escape($this->completedResult)
                . ($this->resultToRevisit !== null ? Literal::Vertical_Line->value : '') . Character::escape($this->resultToRevisit),
            TransliterationDirection::REVERSE => Character::escape($this->completedResult)
                . ($this->resultToRevisit !== null ? Literal::Vertical_Line->value : '') . Character::escape($this->resultToRevisit)
                . Literal::Less_Than_Sign->value
                . Character::escape($this->beforeContext) . ($this->beforeContext !== null ? Literal::Left_Curly_Bracket->value : '')
                . Character::escape($this->textToReplace)
                . ($this->afterContext !== null ? Literal::Right_Curly_Bracket->value : '') . Character::escape($this->afterContext),
        };
    }
}
