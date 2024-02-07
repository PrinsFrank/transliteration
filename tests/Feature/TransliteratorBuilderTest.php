<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Feature;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\ConversionSet\IPAToEnglishApproximation;
use PrinsFrank\Transliteration\ConversionSet\Keep;
use PrinsFrank\Transliteration\ConversionSet\Replace;
use PrinsFrank\Transliteration\ConversionSet\ToASCII;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use PrinsFrank\Transliteration\Exception\UnableToCreateTransliteratorException;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Character;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Filter;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @coversNothing */
class TransliteratorBuilderTest extends TestCase
{
    /**
     * Examples from prinsfrank/standards
     *
     * @throws InvalidArgumentException
     * @throws UnableToCreateTransliteratorException
     */
    public function testTransliteratorBuilderWithScriptNamesInNativeLanguages(): void
    {
        $transliterator = (new TransliteratorBuilder())
            ->applyConversionSet(new ToASCII())
            ->applyConversionSet(
                new Keep(
                    (new Filter())
                        ->addRange(new Character('a'), new Character('z'))
                        ->addRange(new Character('A'), new Character('Z'))
                        ->addChar(new Character(' '))
                )
            )
        ;

        static::assertSame(
            'Naxi Geba naci geba Na Khi Ggo baw Nakhi Geba',
            $transliterator->transliterate('Naxi Geba (na²¹ɕi³³ ge²¹ba²¹, \'Na \'Khi ²Ggŏ ¹baw, Nakhi Geba)')
        );
        static::assertSame(
            'Medefaidrin Oberi Okaime Oberi Okaime',
            $transliterator->transliterate('Medefaidrin (Oberi Okaime, Oberi Okaimɛ)')
        );
    }

    /**
     * Examples from prinsfrank/standards
     *
     * @throws InvalidArgumentException
     * @throws UnableToCreateTransliteratorException
     */
    public function testTransliteratorBuilderWithScriptNamesInNativeLanguagesWithReplacements(): void
    {
        $transliterator = (new TransliteratorBuilder())
            ->applyConversionSets(
                [
                    new ToASCII(),
                    new Replace('-', ' '),
                    new IPAToEnglishApproximation(),
                    new Keep(
                        (new Filter())
                            ->addRange(new Character('a'), new Character('z'))
                            ->addRange(new Character('A'), new Character('Z'))
                            ->addChar(new Character(' '))
                    )
                ]
            );

        static::assertSame(
            'Naxi Geba naci guba Na Khi Ggo baw Nakhi Geba',
            $transliterator->transliterate('Naxi Geba (na²¹ɕi³³ gʌ²¹ba²¹, \'Na-\'Khi ²Ggŏ-¹baw, Nakhi Geba)')
        );
        static::assertSame(
            'Medefaidrin Oberi Okaime Oberi Okaime',
            $transliterator->transliterate('Medefaidrin (Oberi Okaime, Oberi Ɔkaimɛ)')
        );
    }
}
