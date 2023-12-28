<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\Components;

use Stringable;

final class Filter implements Stringable
{
    public function __toString(): string
    {
        return '[' . ']';
    }
}
