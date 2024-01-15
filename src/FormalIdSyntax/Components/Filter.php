<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\FormalIdSyntax\Components;

use PrinsFrank\Transliteration\Enum\Literal;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use Stringable;

/**
 * @see https://unicode-org.github.io/icu/userguide/transforms/general/#formal-id-syntax
 * "Finally, ‘filter’ is a valid UnicodeSet pattern."
 * @see https://unicode-org.github.io/icu/userguide/strings/unicodeset.html
 */
final class Filter implements Stringable
{
    /** @var list<string|array{0: string, 1: string}> */
    private array $set = [];

    private bool $inverse = false;

    public function __toString(): string
    {
        $string = Literal::Square_Bracket_Open->value;
        if ($this->inverse === true) {
            $string .= Literal::Caret->value;
        }

        foreach ($this->set as $set) {
            if (is_string($set)) {
                $string .= $set;
            } else {
                $string .= $set[0] . Literal::Dash->value . $set[1];
            }
        }

        return $string . Literal::Square_Bracket_Close->value;
    }

    /** @throws InvalidArgumentException */
    public function addRange(string $from, string $to): self
    {
        if (mb_strlen($from) !== 1 || mb_strlen($to) !== 1) {
            throw new InvalidArgumentException('Only single multibyte characters are allowed');
        }

        $this->set[] = [$from, $to];

        return $this;
    }

    /** @throws InvalidArgumentException */
    public function addChar(string $char): self
    {
        if (mb_strlen($char) !== 1) {
            throw new InvalidArgumentException('Only single multibyte characters are allowed');
        }

        if ($char === ' ') {
            $char = '\u0020';
        }

        $this->set[] = $char;

        return $this;
    }

    public function inverse(): self
    {
        $this->inverse = true;

        return $this;
    }
}
