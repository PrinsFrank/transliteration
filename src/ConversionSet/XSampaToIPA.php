<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\ConversionSet;

use PrinsFrank\Transliteration\Syntax\Enum\SpecialTag;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @api */
class XSampaToIPA implements ConversionSet
{
    public function apply(TransliteratorBuilder $transliteratorBuilder): void
    {
        $transliteratorBuilder->applyConversionSet(
            new ConvertScriptLanguage(SpecialTag::XSampa, SpecialTag::IPA)
        );
    }
}
