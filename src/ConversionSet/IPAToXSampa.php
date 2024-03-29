<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\ConversionSet;

use PrinsFrank\Transliteration\ConversionSet;
use PrinsFrank\Transliteration\Exception\RecursionException;
use PrinsFrank\Transliteration\Syntax\Enum\SpecialTag;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @api */
final class IPAToXSampa implements ConversionSet
{
    /** @throws RecursionException */
    public function apply(TransliteratorBuilder $transliteratorBuilder): void
    {
        $transliteratorBuilder->applyConversionSet(
            new ScriptLanguage(SpecialTag::IPA, SpecialTag::XSampa)
        );
    }
}
