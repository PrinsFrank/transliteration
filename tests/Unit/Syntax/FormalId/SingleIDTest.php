<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\Syntax\FormalId;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Scripts\ScriptAlias;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\BasicID;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Character;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Filter;
use PrinsFrank\Transliteration\Syntax\FormalId\SingleID;

/** @coversDefaultClass \PrinsFrank\Transliteration\Syntax\FormalId\SingleID */
class SingleIDTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::__toString
     */
    public function testToString(): void
    {
        static::assertSame('Latin', (new SingleID(new BasicID(ScriptAlias::Latin)))->__toString());
        static::assertSame('Latin', (new SingleID(new BasicID(ScriptAlias::Latin), new Filter()))->__toString());
        static::assertSame('[a]Latin', (new SingleID(new BasicID(ScriptAlias::Latin), (new Filter())->addChar(new Character('a'))))->__toString());
    }
}
