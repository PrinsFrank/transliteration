<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\Transliterator;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\ConversionSet;
use PrinsFrank\Transliteration\Exception\RecursionException;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @coversDefaultClass \PrinsFrank\Transliteration\Transliterator\RecursionHandler */
class RecursionHandlerTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::applyConversionSet
     */
    public function testApplyConversionSetWithRecursion(): void
    {
        $this->expectException(RecursionException::class);
        $this->expectExceptionMessage(
            '"' . TestConversionSetA::class . '" has already been applied:' . PHP_EOL .
            '"' . TestConversionSetA::class . '"' . PHP_EOL .
            'calls "' . TestConversionSetB::class . '"' . PHP_EOL .
            'calls "' . TestConversionSetC::class . '"' . PHP_EOL .
            'which tries to call "' . TestConversionSetA::class . '" again'
        );

        (new TransliteratorBuilder())
            ->applyConversionSet(new TestConversionSetA());
    }

    /**
     * @covers ::applyConversionSet
     *
     * @throws RecursionException
     */
    public function testApplyConversionSetWithoutRecursion(): void
    {
        (new TransliteratorBuilder())
            ->applyConversionSet(new TestConversionSetD());

        $this->addToAssertionCount(1);
    }
}

class TestConversionSetA implements ConversionSet
{
    /** @throws RecursionException */
    public function apply(TransliteratorBuilder $transliteratorBuilder): void
    {
        $transliteratorBuilder->applyConversionSet(new TestConversionSetB());
    }
}

class TestConversionSetB implements ConversionSet
{
    /** @throws RecursionException */
    public function apply(TransliteratorBuilder $transliteratorBuilder): void
    {
        $transliteratorBuilder->applyConversionSet(new TestConversionSetC());
    }
}

class TestConversionSetC implements ConversionSet
{
    /** @throws RecursionException */
    public function apply(TransliteratorBuilder $transliteratorBuilder): void
    {
        $transliteratorBuilder->applyConversionSet(new TestConversionSetA());
    }
}

class TestConversionSetD implements ConversionSet
{
    /** @throws RecursionException */
    public function apply(TransliteratorBuilder $transliteratorBuilder): void
    {
        $transliteratorBuilder->applyConversionSet(new TestConversionSetE());
    }
}

class TestConversionSetE implements ConversionSet
{
    /** @throws RecursionException */
    public function apply(TransliteratorBuilder $transliteratorBuilder): void
    {
        $transliteratorBuilder->applyConversionSet(new TestConversionSetF());
    }
}

class TestConversionSetF implements ConversionSet
{
    public function apply(TransliteratorBuilder $transliteratorBuilder): void
    {
    }
}
