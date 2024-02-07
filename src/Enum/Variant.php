<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Enum;

/** @api */
enum Variant: string
{
    /** Default for historical reasons */
    case Java = 'Java';

    case Unicode = 'Unicode';
    case Perl    = 'Perl';
    case XML     = 'XML';

    /** United Nations Group of Experts on Geographical Names */
    case UNGEGN = 'UNGEGN';
}
