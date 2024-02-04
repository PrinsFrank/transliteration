<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration;

use PrinsFrank\Transliteration\Enum\TransliterationDirection;
use PrinsFrank\Transliteration\Exception\ListIDsUnavailableException;
use PrinsFrank\Transliteration\Exception\UnableToCreateTransliteratorException;
use PrinsFrank\Transliteration\FormalIdSyntax\CompoundID;
use PrinsFrank\Transliteration\FormalIdSyntax\SingleID;
use PrinsFrank\Transliteration\Rule\RuleList;
use Transliterator;

class TypedTransliterator implements TypedTransliteratorInterface
{
    /** @throws UnableToCreateTransliteratorException */
    public function create(
        SingleID|CompoundID|RuleList $id,
        TransliterationDirection $direction = TransliterationDirection::FORWARD
    ): Transliterator {
        $transliteratorDirection = match ($direction) {
            TransliterationDirection::FORWARD => Transliterator::FORWARD,
            TransliterationDirection::REVERSE => Transliterator::REVERSE,
        };

        $transliterator = match (get_class($id)) {
            RuleList::class => Transliterator::createFromRules($id->__toString(), $transliteratorDirection),
            default         => Transliterator::create($id->__toString(), $transliteratorDirection)
        };

        return $transliterator ?? throw new UnableToCreateTransliteratorException(intl_get_error_message(), $id->__toString());
    }

    /** @throws UnableToCreateTransliteratorException */
    public function transliterate(
        string $string,
        SingleID|CompoundID|RuleList $id,
        TransliterationDirection $direction = TransliterationDirection::FORWARD
    ): string {
        $transliteratedString = transliterator_transliterate($this->create($id, $direction), $string);
        if ($transliteratedString === false) {
            throw new UnableToCreateTransliteratorException(intl_get_error_message(), $id->__toString());
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
            // @codeCoverageIgnoreStart
            throw new ListIDsUnavailableException();
            // @codeCoverageIgnoreEnd
        }

        return $ids;
    }
}
