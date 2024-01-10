<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\Tests\Feature;

use PHPUnit\Framework\TestCase;
use PrinsFrank\TransliteratorWrapper\TransliteratorBuilder;

/** @coversNothing */
class TransliteratorBuilderTest extends TestCase
{
    /** Examples from prinsfrank/standards */
    public function testTransliteratorBuilderWithScriptNamesInNativeLanguages(): void
    {
        $transliterator = (new TransliteratorBuilder())
            ->toASCII()
            ->toXSampa();

        static::assertSame('Naxi Geba naci geba Na Khi Ggo baw Nakhi Geba', $transliterator->transliterate('Naxi Geba (na²¹ɕi³³ gʌ²¹ba²¹, \'Na-\'Khi ²Ggŏ-¹baw, Nakhi Geba)'));
        static::assertSame('Medefaidrin Oberi Okaime Oberi Okaime', $transliterator->transliterate('Medefaidrin (Oberi Okaime, Oberi Ɔkaimɛ)'));
    }
}
