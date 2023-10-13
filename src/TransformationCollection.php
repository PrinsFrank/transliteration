<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper;

use PrinsFrank\TransliteratorWrapper\Transformation\TransformationInterface;
use Stringable;

class TransformationCollection
{
    /** @var array<TransformationInterface> */
    private array $transformations;

    public function __construct(
        TransformationInterface ... $transformations
    ) {
        foreach ($transformations as $transformation) {
            $this->addTransformation($transformation);
        }
    }

    private function addTransformation(TransformationInterface $transformation): static
    {
        $this->transformations[] = $transformation;

        return $this;
    }

    public function getIdentifierString(): string
    {
        $string = '';
        foreach ($this->transformations as $transformation) {
            $string .= $transformation->getIdentifierString();
        }

        return $string;
    }
}
