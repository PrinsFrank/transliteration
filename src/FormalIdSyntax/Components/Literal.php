<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components;

/** @see https://unicode-org.github.io/icu/userguide/transforms/general/#formal-id-syntax */
enum Literal: string
{
    case Semicolon            = ';';
    case Dash                 = '-';
    case Slash                = '/';
    case Square_Bracket_Open  = '[';
    case Square_Bracket_Close = ']';
    case Caret                = '^';
}
