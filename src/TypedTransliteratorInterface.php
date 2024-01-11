<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper;

use PrinsFrank\TransliteratorWrapper\Enum\TransliterationDirection;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\CompoundID;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\SingleID;
use Transliterator;

interface TypedTransliteratorInterface
{
    public function create(SingleID|CompoundID $id, TransliterationDirection $direction = TransliterationDirection::FORWARD): Transliterator;

    public function transliterate(string $string, SingleID|CompoundID $id, TransliterationDirection $direction = TransliterationDirection::FORWARD): string;

    /** @return list<string> */
    public function listIDs(): array;
}
