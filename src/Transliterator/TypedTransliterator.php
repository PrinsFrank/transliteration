<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Transliterator;

use PrinsFrank\Transliteration\Enum\TransliterationDirection;
use PrinsFrank\Transliteration\Exception\ListIDsUnavailableException;
use PrinsFrank\Transliteration\Exception\UnableToCreateTransliteratorException;
use PrinsFrank\Transliteration\FormalIdSyntax\CompoundID;
use PrinsFrank\Transliteration\FormalIdSyntax\SingleID;
use PrinsFrank\Transliteration\Rule\RuleList;
use Transliterator;

/** @internal */
final class TypedTransliterator
{
    /** @throws UnableToCreateTransliteratorException */
    public static function create(
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
    public static function transliterate(
        string $string,
        SingleID|CompoundID|RuleList $id,
        TransliterationDirection $direction = TransliterationDirection::FORWARD
    ): string {
        $transliteratedString = transliterator_transliterate(self::create($id, $direction), $string);
        if ($transliteratedString === false) {
            // @codeCoverageIgnoreStart
            throw new UnableToCreateTransliteratorException(intl_get_error_message(), $id->__toString());
            // @codeCoverageIgnoreEnd
        }

        return $transliteratedString;
    }

    /**
     * @throws ListIDsUnavailableException
     * @return list<string>
     */
    public static function listIDs(): array
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
