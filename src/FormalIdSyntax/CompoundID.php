<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\FormalIdSyntax;

use PrinsFrank\TransliteratorWrapper\Exception\InvalidArgumentException;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\BasicID;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\Components\Filter;

/**
 * @see https://unicode-org.github.io/icu/userguide/transforms/general/#formal-id-syntax
 */
final class CompoundID implements ID
{
    /** @throws InvalidArgumentException */
    public function __construct(
        public readonly Filter|null $globalFilter = null,
        public readonly array $basicIds,
    ) {
        array_map(static function (mixed $basicId) { $basicId instanceof BasicID || throw new InvalidArgumentException('Param $basicIDs should be an array of "' . BasicID::class . '"');}, $this->basicIds);
    }

    public function __toString()
    {
        $string = '';
        if ($this->globalFilter !== null) {
            $string .= $this->globalFilter->__toString() . ' ;';
        }

        return $string;
    }
}
