<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Tests\Unit\ConversionSet;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Transliteration\ConversionSet\Remove;
use PrinsFrank\Transliteration\Syntax\Enum\SpecialTag;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\BasicID;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Character;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Filter;
use PrinsFrank\Transliteration\Syntax\FormalId\SingleID;
use PrinsFrank\Transliteration\TransliteratorBuilder;

/** @coversDefaultClass \PrinsFrank\Transliteration\ConversionSet\Remove */
class RemoveTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::apply
     */
    public function testApply(): void
    {
        static::assertEquals(
            [
                new SingleID(new BasicID(SpecialTag::Remove), (new Filter())->addChar(new Character('a')))
            ],
            (new TransliteratorBuilder())
                ->applyConversionSet(new Remove((new Filter())->addChar(new Character('a'))))
                ->getConversions()
        );
    }
}
