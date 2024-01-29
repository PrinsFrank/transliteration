<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Rule\Components;

use PrinsFrank\Transliteration\Enum\Literal;
use Stringable;

final class VariableDefinition implements Stringable
{
    public function __construct(
        public readonly string $name,
        public readonly string $value,
    ) {
    }

    public function __toString(): string
    {
        return Literal::Dollar_Sign->value . $this->name . Literal::Space->value . Literal::Equals_Sign->value . Literal::Space->value . $this->value . Literal::Semicolon->value;
    }
}
