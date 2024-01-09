<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\Components;

enum Literal: string
{
    case Semicolon = ';';
    case Dash = '-';
    case Slash = '/';
    case Square_Bracket_Open = '[';
    case Square_Bracket_Close = ']';
}
