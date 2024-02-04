<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\Exception\UnableToCreateTransliteratorException;

/** @coversDefaultClass \PrinsFrank\Transliteration\Exception\UnableToCreateTransliteratorException */
class UnableToCreateTransliteratorExceptionTest extends TestCase
{
    /** @covers ::__construct */
    public function testConstruct(): void
    {
        $this->expectException(UnableToCreateTransliteratorException::class);
        $this->expectExceptionMessage('Foo with id "Bar"');
        throw new UnableToCreateTransliteratorException('Foo', 'Bar');
    }
}
