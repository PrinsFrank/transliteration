<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration;

/** @api */
interface ConversionSet
{
    public function apply(TransliteratorBuilder $transliteratorBuilder): void;
}
