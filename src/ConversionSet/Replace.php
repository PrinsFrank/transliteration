<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\ConversionSet;

use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use PrinsFrank\Transliteration\Syntax\Rule\Components\Conversion;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @api */
class Replace implements ConversionSet
{
    /** @param non-empty-string $string */
    public function __construct(
        private readonly string $string,
        private readonly string $with
    ) {
    }

    /** @throws InvalidArgumentException */
    public function apply(TransliteratorBuilder $transliteratorBuilder): void
    {
        $transliteratorBuilder->addConversion(new Conversion($this->string, $this->with));
    }
}
