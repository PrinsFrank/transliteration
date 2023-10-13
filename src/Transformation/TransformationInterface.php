<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\Transformation;

interface TransformationInterface
{
    public function getIdentifierString(): string;
}
