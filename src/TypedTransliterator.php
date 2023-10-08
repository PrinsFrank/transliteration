<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper;

use PrinsFrank\TransliteratorWrapper\Enum\TransliterationDirection;
use PrinsFrank\TransliteratorWrapper\Exception\InvalidArgumentException;
use PrinsFrank\TransliteratorWrapper\Exception\ListIDsUnavailableException;
use Transliterator;

class TypedTransliterator
{
    /** @throws InvalidArgumentException */
    public static function create(
        TransformationCollection $transformationCollection,
        TransliterationDirection $direction = TransliterationDirection::FORWARD
    ): Transliterator {
        return Transliterator::create(
            $transformationCollection->__toString(),
            match ($direction) {
                TransliterationDirection::FORWARD => Transliterator::FORWARD,
                TransliterationDirection::REVERSE => Transliterator::REVERSE,
            }
        );
    }

    /** @throws InvalidArgumentException */
    public static function createInverse(TransformationCollection $transformationCollection): Transliterator
    {
        return static::create($transformationCollection, TransliterationDirection::REVERSE);
    }

    /** @throws ListIDsUnavailableException */
    public static function listIDs(): array
    {
        $ids = Transliterator::listIDs();
        if ($ids === false) {
            throw new ListIDsUnavailableException();
        }

        return $ids;
    }
}
