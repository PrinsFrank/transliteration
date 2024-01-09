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
        public readonly BasicID $basicID,
        public readonly Filter|null $globalFilter = null,
    ) { }

    public function __toString(): string
    {
        $string = '';
        if ($this->globalFilter !== null) {
            $string .= $this->globalFilter->__toString() . Literal::Semicolon->value;
        }

        return $string . $this->basicID->__toString();
    }
}
