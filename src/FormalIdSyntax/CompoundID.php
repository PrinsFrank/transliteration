<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\FormalIdSyntax;

use PrinsFrank\TransliteratorWrapper\Exception\InvalidArgumentException;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\Filter;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\Literal;

/** @see https://unicode-org.github.io/icu/userguide/transforms/general/#formal-id-syntax */
final class CompoundID implements ID
{
    /** @param list<SingleID> $singleIds */
    public function __construct(
        public readonly array       $singleIds,
        public readonly Filter|null $globalFilter = null,
    ) {
        // @phpstan-ignore-next-line
        array_map(static function (mixed $singleId) { $singleId instanceof SingleID || throw new InvalidArgumentException('Param $singleIds should be an array of "' . SingleID::class . '"');}, $this->singleIds);
    }

    public function __toString()
    {
        $string = '';
        if ($this->globalFilter !== null) {
            $string .= $this->globalFilter->__toString() . Literal::Semicolon->value;
        }

        foreach ($this->singleIds as $singleId) {
            $string .= $singleId->__toString() . Literal::Semicolon->value;
        }

        return $string;
    }
}
