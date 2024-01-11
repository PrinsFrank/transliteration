<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PrinsFrank\TransliteratorWrapper\Exception\ListIDsUnavailableException;
use PrinsFrank\TransliteratorWrapper\TypedTransliterator;

/** @coversDefaultClass \PrinsFrank\TransliteratorWrapper\TypedTransliterator */
class TypedTransliteratorTest extends TestCase
{
    /**
     * @covers ::listIDs
     * @throws ListIDsUnavailableException
     */
    public function testListIDs(): void
    {
        static::assertNotEmpty((new TypedTransliterator())->listIDs());
    }
}
