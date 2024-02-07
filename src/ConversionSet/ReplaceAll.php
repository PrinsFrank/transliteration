<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\ConversionSet;

use PrinsFrank\Transliteration\ConversionSet;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use PrinsFrank\Transliteration\Syntax\Rule\Components\Conversion;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @api */
final class ReplaceAll implements ConversionSet
{
    /**
     * @param non-empty-array<non-empty-string> $strings
     * @throws InvalidArgumentException
     */
    public function __construct(
        private readonly array  $strings,
        private readonly string $with
    ) {
        if ($strings === []) {
            throw new InvalidArgumentException('param $strings should be a non-empty array');
        }
    }

    /** @throws InvalidArgumentException */
    public function apply(TransliteratorBuilder $transliteratorBuilder): void
    {
        foreach ($this->strings as $string) {
            $transliteratorBuilder->addConversion(new Conversion($string, $this->with));
        }
    }
}
