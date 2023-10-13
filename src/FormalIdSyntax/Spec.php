<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\FormalIdSyntax;

use PrinsFrank\Standards\Scripts\ScriptName;

/**
 * @see https://unicode-org.github.io/icu/userguide/transforms/general/#formal-id-syntax
 */
class Spec
{
    public function __construct(
        public readonly ScriptName|LocaleName|Identifier $scriptLocaleOrIdentifier,
    ) { }

    public function __toString(): string
    {
    }
}
