<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Enum;

/** @see https://unicode-org.github.io/icu/userguide/transforms/general/#formal-id-syntax */
enum Literal: string
{
    case Colon                = ':';
    case Semicolon            = ';';
    case Dash                 = '-';
    case Slash                = '/';
    case Square_Bracket_Open  = '[';
    case Square_Bracket_Close = ']';
    case Caret                = '^';
    case DollarSign           = '$';
    case EqualsSign           = '=';
    case Space                = ' ';
    case Left_Curly_Bracket   = '{';
    case Right_Curly_Bracket  = '}';
    case Greater_Than_Sign    = '>';
    case Less_Than_Sign       = '<';
    case Vertical_Line        = '|';
    case Low_Line             = '_'; // No current usage but is an escaped character in conversions - See Character::Escape for indirect usage.
}
