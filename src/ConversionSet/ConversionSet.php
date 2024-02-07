<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\ConversionSet;

use PrinsFrank\Transliteration\TransliteratorBuilder;

interface ConversionSet
{
    public function apply(TransliteratorBuilder $transliteratorBuilder): void;
}
