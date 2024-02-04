<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\FormalIdSyntax;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Scripts\ScriptAlias;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\BasicID;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Character;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Filter;
use PrinsFrank\Transliteration\FormalIdSyntax\CompoundID;
use PrinsFrank\Transliteration\FormalIdSyntax\SingleID;

/** @coversDefaultClass \PrinsFrank\Transliteration\FormalIdSyntax\CompoundID */
class CompoundIDTest extends TestCase
{
    /** @covers ::__construct */
    public function testConstructWhenInvalidIDSupplied(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Param $singleIds should be an array of "PrinsFrank\Transliteration\FormalIdSyntax\SingleID"');
        /** @phpstan-ignore-next-line */
        new CompoundID([null]);
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     */
    public function testToString(): void
    {
        static::assertSame(
            'Latin;',
            (new CompoundID([
                new SingleID(new BasicID(ScriptAlias::Latin))
            ]))->__toString()
        );
        static::assertSame(
            'Latin;Cyrillic;',
            (new CompoundID([
                new SingleID(new BasicID(ScriptAlias::Latin)),
                new SingleID(new BasicID(ScriptAlias::Cyrillic))
            ]))->__toString()
        );
        static::assertSame(
            '[a-z];Latin;Cyrillic;',
            (new CompoundID(
                [
                    new SingleID(new BasicID(ScriptAlias::Latin)),
                    new SingleID(new BasicID(ScriptAlias::Cyrillic))
                ],
                (new Filter())->addRange(new Character('a'), new Character('z'))
            ))->__toString()
        );
    }
}
