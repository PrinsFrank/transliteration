<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper\Builder;

use PrinsFrank\TransliteratorWrapper\Enum\TransliterationDirection;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\Components\Filter;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\CompoundID;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\SingleID;
use PrinsFrank\TransliteratorWrapper\TypedTransliterator;
use PrinsFrank\TransliteratorWrapper\TypedTransliteratorInterface;
use Transliterator;

class TransliteratorBuilder
{
    private TransliterationDirection $direction = TransliterationDirection::FORWARD;

    /** @var list<SingleID> */
    private array $singleIDS = [];

    private Filter|null $globalFilter = null;

    private readonly TypedTransliteratorInterface $typedTransliterator;

    public function __construct(TypedTransliteratorInterface|null $typedTransliterator) {
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
