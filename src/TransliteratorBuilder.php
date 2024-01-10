<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper;

use PrinsFrank\TransliteratorWrapper\Enum\TransliterationDirection;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\Filter;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\CompoundID;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\SingleID;
use Transliterator;

class TransliteratorBuilder
{
    /** @var list<SingleID> */
    private array $singleIDS = [];

    private readonly TypedTransliteratorInterface $typedTransliterator;

    public function __construct(
        TypedTransliteratorInterface|null $typedTransliterator = null,
        private TransliterationDirection $direction = TransliterationDirection::FORWARD,
        private Filter|null $globalFilter = null,
    ) {
        $this->typedTransliterator = $typedTransliterator ?? new TypedTransliterator();
    }

    public function setDirection(TransliterationDirection $direction): static
    {
        $this->direction = $direction;

        return $this;
    }

    public function setGlobalFilter(Filter|null $globalFilter): static
    {
        $this->globalFilter = $globalFilter;

        return $this;
    }

    public function addSingleID(SingleID $singleID): static
    {
        $this->singleIDS[] = $singleID;

        return $this;
    }

    public function getTransliterator(): Transliterator
    {
        if ($this->globalFilter === null && count($this->singleIDS) === 1) {
            $this->typedTransliterator->create($this->singleIDS[0], $this->direction);
        }

        return $this->typedTransliterator->create(new CompoundID($this->singleIDS, $this->globalFilter), $this->direction);
    }
}
