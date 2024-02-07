<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\ConversionSet;

use PrinsFrank\Standards\LanguageTag\LanguageTag;
use PrinsFrank\Standards\Scripts\ScriptAlias;
use PrinsFrank\Standards\Scripts\ScriptName;
use PrinsFrank\Transliteration\ConversionSet;
use PrinsFrank\Transliteration\Syntax\Enum\SpecialTag;
use PrinsFrank\Transliteration\Syntax\Enum\Variant;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\BasicID;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Filter;
use PrinsFrank\Transliteration\Syntax\FormalId\SingleID;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @api */
final class ConvertScriptLanguage implements ConversionSet
{
    public function __construct(
        private readonly ScriptName|ScriptAlias|LanguageTag|SpecialTag $source,
        private readonly ScriptName|ScriptAlias|LanguageTag|SpecialTag $target,
        private readonly Variant|null $variant = null,
        private readonly Filter|null $filter = null,
    ) {
    }

    public function apply(TransliteratorBuilder $transliteratorBuilder): void
    {
        $transliteratorBuilder->addSingleID(new SingleID(new BasicID($this->target, $this->source, $this->variant), $this->filter));
    }
}
