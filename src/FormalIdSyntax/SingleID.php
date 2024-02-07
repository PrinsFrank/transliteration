<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\FormalIdSyntax;

use PrinsFrank\Transliteration\FormalIdSyntax\Components\BasicID;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Filter;

/**
 * @api
 * @see https://unicode-org.github.io/icu/userguide/transforms/general/#formal-id-syntax
 */
final class SingleID implements ID
{
    public function __construct(
        public readonly BasicID     $basicID,
        public readonly Filter|null $filter = null,
    ) {
    }

    public function __toString(): string
    {
        $string = '';
        if ($this->filter !== null) {
            $string .= $this->filter->__toString();
        }

        return $string . $this->basicID->__toString();
    }
}
