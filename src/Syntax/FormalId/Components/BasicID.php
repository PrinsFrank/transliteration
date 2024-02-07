<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Syntax\FormalId\Components;

use PrinsFrank\Standards\LanguageTag\LanguageTag;
use PrinsFrank\Standards\LanguageTag\SubtagSeparator;
use PrinsFrank\Standards\Scripts\ScriptAlias;
use PrinsFrank\Standards\Scripts\ScriptName;
use PrinsFrank\Transliteration\Syntax\Enum\Literal;
use PrinsFrank\Transliteration\Syntax\Enum\SpecialTag;
use PrinsFrank\Transliteration\Syntax\Enum\Variant;
use Stringable;

/**
 * @api
 *
 * @see https://unicode-org.github.io/icu/userguide/transforms/general/#basic-ids
 */
final class BasicID implements Stringable
{
    public function __construct(
        public readonly ScriptName|ScriptAlias|LanguageTag|SpecialTag $target,
        public readonly ScriptName|ScriptAlias|LanguageTag|SpecialTag|null $source = null,
        public readonly Variant|null $variant = null,
    ) {
    }

    public function __toString(): string
    {
        $string = '';
        if ($this->source !== null) {
            $string .= ($this->source instanceof LanguageTag ? $this->source->toString(SubtagSeparator::UNDERSCORE) : $this->source->value) . Literal::Dash->value;
        }

        $string .= $this->target instanceof LanguageTag ? $this->target->toString(SubtagSeparator::UNDERSCORE) : $this->target->value;
        if ($this->variant !== null) {
            $string .= Literal::Slash->value . $this->variant->value;
        }

        return $string;
    }
}
