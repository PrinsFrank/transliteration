<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\TypedTransliterator;

/** @coversDefaultClass \PrinsFrank\Transliteration\TypedTransliterator */
class TypedTransliteratorTest extends TestCase
{
    /** @covers ::listIDs */
    public function testListIDs(): void
    {
        static::assertNotEmpty((new TypedTransliterator())->listIDs());
    }
}
