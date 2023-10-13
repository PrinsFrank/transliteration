<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\FormalIdSyntax;

/**
 * @see https://unicode-org.github.io/icu/userguide/transforms/general/#formal-id-syntax
 */
class BasicID
{
    public function __construct(
        public readonly Spec $specA,
        public readonly Spec|null $specB = null,
        public readonly Identifier|null $identifier = null,
    ) {
    }

    public function __toString(): string
    {
        $string = $this->specA->__toString();
        if ($this->specB !== null) {
            $string .= '-' . $this->specB->__toString();
        }

        if ($this->identifier !== null) {
            $string .= '/' . $this->identifier->__toString();
        }

        return $string;
    }
}
