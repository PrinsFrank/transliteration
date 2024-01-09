<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper;

use PrinsFrank\TransliteratorWrapper\Enum\TransliterationDirection;
use PrinsFrank\TransliteratorWrapper\Exception\ListIDsUnavailableException;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\BasicID;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\CompoundID;
use Transliterator;

class TypedTransliterator implements TypedTransliteratorInterface
{
    public function create(
        BasicID|CompoundID       $id,
        TransliterationDirection $direction = TransliterationDirection::FORWARD
    ): Transliterator {
        return Transliterator::create(
            $id->__toString(),
            match ($direction) {
                TransliterationDirection::FORWARD => Transliterator::FORWARD,
                TransliterationDirection::REVERSE => Transliterator::REVERSE,
            }
        );
    }

    /** @throws ListIDsUnavailableException */
    public function listIDs(): array
    {
        $ids = Transliterator::listIDs();
        if ($ids === false) {
            throw new ListIDsUnavailableException();
        }

        return $ids;
    }
}
