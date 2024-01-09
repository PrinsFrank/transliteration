<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper;

use PrinsFrank\TransliteratorWrapper\Enum\TransliterationDirection;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\BasicID;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\CompoundID;
use Transliterator;

interface TypedTransliteratorInterface
{
    public function create(BasicID|CompoundID $id, TransliterationDirection $direction = TransliterationDirection::FORWARD): Transliterator;

    public function transliterate(string $string, BasicID|CompoundID $id, TransliterationDirection $direction = TransliterationDirection::FORWARD): string;

    public function listIDs(): array;
}
