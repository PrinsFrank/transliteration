<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\ConversionSet;

use PrinsFrank\Transliteration\Syntax\Enum\SpecialTag;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\BasicID;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Filter;
use PrinsFrank\Transliteration\Syntax\FormalId\SingleID;
use PrinsFrank\Transliteration\TransliteratorBuilder;

class Remove implements ConversionSet
{
    public function __construct(
        private readonly Filter $filter,
    ) {
    }

    public function apply(TransliteratorBuilder $transliteratorBuilder): void
    {
        $transliteratorBuilder->addSingleID(new SingleID(new BasicID(SpecialTag::Remove), $this->filter));
    }
}
