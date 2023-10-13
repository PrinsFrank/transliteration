<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\Transformation;

class TitleCaseTransformation implements TransformationInterface
{
    public function __construct(
        private readonly ?string $onlyChars = null
    ) { }

    public function getIdentifierString(): string
    {
        $string = '';
        if ($this->onlyChars !== null & $this->onlyChars !== '') {
            $string .= '[' . $this->onlyChars . '] ';
        }

        return $string . 'Title';
    }
}
