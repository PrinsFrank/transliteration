<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Enum;

enum SpecialTag: string
{
    case Any    = 'Any';
    case Hex    = 'Hex';
    case Null   = 'Null';
    case Remove = 'Remove';
    case ASCII  = 'ASCII';
    case IPA    = 'IPA';
    case XSampa = 'XSampa';
}
