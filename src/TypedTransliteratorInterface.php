<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration;

use PrinsFrank\Transliteration\Enum\TransliterationDirection;
use PrinsFrank\Transliteration\Exception\UnableToCreateTransliteratorException;
use PrinsFrank\Transliteration\FormalIdSyntax\CompoundID;
use PrinsFrank\Transliteration\FormalIdSyntax\SingleID;
use PrinsFrank\Transliteration\Rule\RuleList;
use Transliterator;

interface TypedTransliteratorInterface
{
    /** @throws UnableToCreateTransliteratorException */
    public function create(SingleID|CompoundID|RuleList $id, TransliterationDirection $direction = TransliterationDirection::FORWARD): Transliterator;

    /** @throws UnableToCreateTransliteratorException */
    public function transliterate(string $string, SingleID|CompoundID|RuleList $id, TransliterationDirection $direction = TransliterationDirection::FORWARD): string;

    /** @return list<string> */
    public function listIDs(): array;
}
