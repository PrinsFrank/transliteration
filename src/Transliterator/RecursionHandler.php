<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Transliterator;

use PrinsFrank\Transliteration\ConversionSet;
use PrinsFrank\Transliteration\Exception\RecursionException;
use PrinsFrank\Transliteration\TransliteratorBuilder;

final class RecursionHandler
{
    /** @var list<class-string<ConversionSet>> */
    private array $recursionCache = [];

    public function __construct(
        private readonly TransliteratorBuilder $transliteratorBuilder
    ) {
    }

    /** @throws RecursionException */
    public function applyConversionSet(ConversionSet $conversionSet): void
    {
        $conversionSetClass = get_class($conversionSet);
        if (in_array($conversionSetClass, $this->recursionCache, true) === true) {
            throw new RecursionException('"' . $conversionSetClass . '" has already been applied:' . PHP_EOL . '"' . implode('"' . PHP_EOL . 'calls "', $this->recursionCache) . '"' . PHP_EOL . 'which tries to call "' . $conversionSetClass . '" again');
        }

        $this->recursionCache[] = $conversionSetClass;
        $conversionSet->apply($this->transliteratorBuilder); // If the 'applyConversionSet' is called, it will traverse further before executing the line below
        $this->recursionCache = [];
    }
}
