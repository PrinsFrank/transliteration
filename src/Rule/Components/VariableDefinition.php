<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Rule\Components;

use PrinsFrank\Transliteration\Enum\Literal;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Character;
use Stringable;

final class VariableDefinition implements Stringable
{
    /**
     * @throws InvalidArgumentException
     *
     * @phpstan-assert non-empty-string $name
     * @phpstan-assert non-empty-string $value
     */
    public function __construct(
        public readonly string $name,
        public readonly string $value,
    ) {
        if ($value === '' || $name === '') {
            throw new InvalidArgumentException('A variable cannot have an empty name or value');
        }

        if (Character::escape($name) !== $name) {
            throw new InvalidArgumentException('A variable cannot contain any special characters');
        }
    }

    public function __toString(): string
    {
        return Literal::Dollar_Sign->value
               . $this->name
               . Literal::Space->value
               . Literal::Equals_Sign->value
               . Literal::Space->value
               . Character::escape($this->value)
               . Literal::Semicolon->value;
    }
}
