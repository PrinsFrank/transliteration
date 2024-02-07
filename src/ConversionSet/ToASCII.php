<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\ConversionSet;

use PrinsFrank\Standards\Scripts\ScriptName;
use PrinsFrank\Transliteration\Syntax\Enum\SpecialTag;
use PrinsFrank\Transliteration\TransliteratorBuilder;

class ToASCII implements ConversionSet
{
    public function apply(TransliteratorBuilder $transliteratorBuilder): void
    {
        $transliteratorBuilder->applyConversionSets(
            [
                new ConvertScriptLanguage(SpecialTag::Any, ScriptName::Latin),
                new ConvertScriptLanguage(ScriptName::Latin, SpecialTag::ASCII)
            ]
        );
    }
}
