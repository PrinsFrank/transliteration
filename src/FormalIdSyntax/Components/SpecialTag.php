<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components;

enum SpecialTag: string
{
    case Any = 'Any';
    case Hex = 'Hex';
    case Null = 'Null';
}
