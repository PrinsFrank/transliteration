<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Syntax\FormalId\Components;

use PrinsFrank\Transliteration\Syntax\Enum\Literal;
use Stringable;

/**
 * @api
 *
 * @see https://unicode-org.github.io/icu/userguide/transforms/general/#formal-id-syntax
 * "Finally, ‘filter’ is a valid UnicodeSet pattern."
 * @see https://unicode-org.github.io/icu/userguide/strings/unicodeset.html
 */
final class Filter implements Stringable
{
    /** @var list<Character|array{0: Character, 1: Character}> */
    private array $set = [];

    public function __construct(
        private readonly bool $inverse = false
    ) {
    }

    public function __toString(): string
    {
        if ($this->set === []) {
            return '';
        }

        $string = Literal::Square_Bracket_Open->value;
        if ($this->inverse === true) {
            $string .= Literal::Caret->value;
        }

        foreach ($this->set as $set) {
            if ($set instanceof Character) {
                $string .= $set->__toString();
            } else {
                $string .= $set[0]->__toString() . Literal::Dash->value . $set[1]->__toString();
            }
        }

        return $string . Literal::Square_Bracket_Close->value;
    }

    public function addRange(Character $from, Character $to): self
    {
        $this->set[] = [$from, $to];

        return $this;
    }

    public function addChar(Character $char): self
    {
        $this->set[] = $char;

        return $this;
    }

    public function getInverse(): self
    {
        $newInstance = new self($this->inverse === false);
        foreach ($this->set as $rangeOrChar) {
            if (is_array($rangeOrChar)) {
                $newInstance->addRange($rangeOrChar[0], $rangeOrChar[1]);
            } else {
                $newInstance->addChar($rangeOrChar);
            }
        }

        return $newInstance;
    }
}
