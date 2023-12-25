<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\FormalIdSyntax;

use PrinsFrank\Standards\LanguageTag\LanguageTag;
use PrinsFrank\Standards\Scripts\ScriptAlias;
use PrinsFrank\Standards\Scripts\ScriptName;

/**
 * @see https://unicode-org.github.io/icu/userguide/transforms/general/#basic-ids
 */
class BasicID
{
    public function __construct(
        public readonly ScriptName|ScriptAlias|LanguageTag|SpecialTag|null $source = null,
        public readonly ScriptName|ScriptAlias|LanguageTag|SpecialTag $target,
        public readonly Variant|null $variant = null,
    ) {
    }

    public function __toString(): string
    {
        $string = '';
        if ($this->source !== null) {
            $string .= ($this->source instanceof LanguageTag ? $this->source->__toString() : $this->source->value) . '-';
        }

        $string .= $this->target instanceof LanguageTag ? $this->target->__toString() : $this->target->value;
        if ($this->variant !== null) {
            $string .= '/' . $this->variant->value;
        }

        return $string;
    }
}
