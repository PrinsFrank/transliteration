<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Syntax\Enum;

/**
 * @api
 *
 * @see https://unicode-org.github.io/icu/userguide/transforms/general/#formal-id-syntax
 */
enum Literal: string
{
    case Space                = ' ';
    case Exclamation_Mark     = '!';
    case Quotation_Mark       = '"';
    case Hash                 = '#';
    case Dollar_Sign          = '$';
    case Percent_Sign         = '%';
    case Ampersand            = '&';
    case Apostrophe           = '\'';
    case Left_Parenthesis     = '(';
    case Right_Parenthesis    = ')';
    case Asterisk             = '*';
    case Plus_Sign            = '+';
    case Comma                = ',';
    case Dash                 = '-';
    case Full_Stop            = '.';
    case Slash                = '/';
    case Colon                = ':';
    case Semicolon            = ';';
    case Less_Than_Sign       = '<';
    case Equals_Sign          = '=';
    case Greater_Than_Sign    = '>';
    case Question_Mark        = '?';
    case At_Sign              = '@';
    case Square_Bracket_Open  = '[';
    case Backslash            = '\\';
    case Square_Bracket_Close = ']';
    case Caret                = '^';
    case Low_Line             = '_';
    case Backtick             = '`';
    case Left_Curly_Bracket   = '{';
    case Vertical_Line        = '|';
    case Right_Curly_Bracket  = '}';
    case Tilde                = '~';
}
