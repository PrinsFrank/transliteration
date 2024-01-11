<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Feature;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\InvalidArgumentException;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Filter;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @coversNothing */
class TransliteratorBuilderTest extends TestCase
{
    /**
     * Examples from prinsfrank/standards
     *
     * @throws InvalidArgumentException
     */
    public function testTransliteratorBuilderWithScriptNamesInNativeLanguages(): void
    {
        $transliterator = (new TransliteratorBuilder())
            ->toASCII()
            ->toXSampa()
            ->keep((new Filter())->addRange('a', 'z')->addRange('A', 'Z')->addChar(' '))
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
}
