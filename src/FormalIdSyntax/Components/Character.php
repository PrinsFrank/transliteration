<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\FormalIdSyntax\Components;

use PrinsFrank\Transliteration\Enum\Literal;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use Stringable;

class Character implements Stringable
{
    /**
     * @phpstan-assert non-empty-string $char
     * @throws InvalidArgumentException
     */
    public function __construct(
        public readonly string $char,
    ) {
        if (mb_strlen($char) !== 1) {
            throw new InvalidArgumentException('$char should be of length 1');
        }
    }

    /**
     * @template T of string|null
     * @param T $string
     * @return T
     */
    public static function escape(?string $string): ?string
    {
        if ($string === null) {
            return null;
        }

        $chars = mb_str_split($string);
        foreach ($chars as $index => $char) {
            if (Literal::tryFrom($char) === null) {
                continue;
            }

            $chars[$index] = '\u' . str_pad(dechex(mb_ord($char)), 4, '0', STR_PAD_LEFT);
        }

        return implode('', $chars);
    }

    public function __toString()
    {
        return self::escape($this->char);
    }
}
