<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components;

use Stringable;

final class Filter implements Stringable
{
    public function __toString(): string
    {
        return Literal::Square_Bracket_Open->value . Literal::Square_Bracket_Close->value;
    }
}
