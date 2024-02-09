<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\ConversionSet;

use PrinsFrank\Transliteration\ConversionSet;
use PrinsFrank\Transliteration\Exception\RecursionException;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @api */
final class IPAToEnglishApproximation implements ConversionSet
{
    /** @throws RecursionException */
    public function apply(TransliteratorBuilder $transliteratorBuilder): void
    {
        $transliteratorBuilder->applyConversionSets(
            [
                new Replace('dʒ', 'g'),
                new Replace('kʰ', 'c'),
                new Replace('kʷ', 'qu'),
                new Replace('kᶣ', 'cu'),
                new Replace('ɫ', 'll'),
                new Replace('ŋ', 'n'),
                new Replace('Ŋ', 'N'),
                new Replace('ɲ', 'n'),
                new Replace('Ɲ', 'N'),
                new Replace('pʰ', 'p'),
                new Replace('ʃ', 'sh'),
                new Replace('Ʃ', 'SH'),
                new Replace('tʰ', 't'),
                new Replace('tʃ', 'ch'),
                new Replace('aː', 'a'),
                new Replace('Aː', 'A'),
                new Replace('ɛ', 'e'),
                new Replace('Ɛ', 'E'),
                new Replace('eː', 'a'),
                new Replace('Eː', 'A'),
                new Replace('ɪ', 'i'),
                new Replace('Ɪ', 'I'),
                new Replace('iː', 'i'),
                new Replace('Iː', 'I'),
                new Replace('ɔ', 'o'),
                new Replace('Ɔ', 'O'),
                new Replace('oː', 'aw'),
                new Replace('ʊ', 'u'),
                new Replace('Ʊ', 'U'),
                new Replace('ʌ', 'u'),
                new Replace('Ʌ', 'U'),
                new Replace('uː', 'u'),
                new Replace('yː', 'u'),
                new Replace('ae̯', 'igh'),
                new Replace('oe̯', 'oy'),
                new Replace('au̯', 'ow'),
                new Replace('ei̯', 'ay'),
                new Replace('ui̯', 'ui'),
            ]
        );
    }
}
