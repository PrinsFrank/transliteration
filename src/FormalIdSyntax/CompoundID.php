<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\FormalIdSyntax;

/**
 * @see https://unicode-org.github.io/icu/userguide/transforms/general/#formal-id-syntax
 */
final class CompoundID
{
    public function __construct(
        public readonly Filter|null $globalFilter = null,
    ) { }
}
