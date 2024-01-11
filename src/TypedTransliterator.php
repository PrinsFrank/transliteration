<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper;

use PrinsFrank\TransliteratorWrapper\Enum\TransliterationDirection;
use PrinsFrank\TransliteratorWrapper\Exception\ListIDsUnavailableException;
use PrinsFrank\TransliteratorWrapper\Exception\UnableToCreateTransliteratorException;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\CompoundID;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\SingleID;
use Transliterator;

class TypedTransliterator implements TypedTransliteratorInterface
{
    /** @throws UnableToCreateTransliteratorException */
    public function create(
        SingleID|CompoundID       $id,
        TransliterationDirection $direction = TransliterationDirection::FORWARD
    ): Transliterator {
        $transliterator = Transliterator::create(
            $id->__toString(),
            match ($direction) {
                TransliterationDirection::FORWARD => Transliterator::FORWARD,
                TransliterationDirection::REVERSE => Transliterator::REVERSE,
            }
        );

        return $transliterator ?? throw new UnableToCreateTransliteratorException(intl_get_error_message());
    }

    /** @throws UnableToCreateTransliteratorException */
    public function transliterate(
        string $string,
        SingleID|CompoundID       $id,
        TransliterationDirection $direction = TransliterationDirection::FORWARD
    ): string {
        $transliteratedString = transliterator_transliterate($this->create($id, $direction), $string);
        if ($transliteratedString === false) {
            throw new UnableToCreateTransliteratorException(intl_get_error_message());
        }

        return $transliteratedString;
    }

    /**
     * @throws ListIDsUnavailableException
     * @return list<string>
     */
    public function listIDs(): array
    {
        $ids = Transliterator::listIDs();
        if ($ids === false) {
            throw new ListIDsUnavailableException();
        }

        return $ids;
    }
}
