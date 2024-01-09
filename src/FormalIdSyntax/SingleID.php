<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\FormalIdSyntax;

use PrinsFrank\TransliteratorWrapper\Exception\InvalidArgumentException;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\BasicID;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\Components\Filter;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\Components\Literal;

/** @see https://unicode-org.github.io/icu/userguide/transforms/general/#formal-id-syntax */
final class SingleID implements ID
{
    /** @throws InvalidArgumentException */
    public function __construct(
        public readonly BasicID     $basicID,
        public readonly Filter|null $filter = null,
    ) { }

    public function __toString(): string
    {
        $string = '';
        if ($this->filter !== null) {
            $string .= $this->filter->__toString() . Literal::Semicolon->value;
        }

        return $string . $this->basicID->__toString();
    }
}
