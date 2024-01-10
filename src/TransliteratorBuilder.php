<?php
declare(strict_types=1);

namespace PrinsFrank\TransliteratorWrapper;

use PrinsFrank\Standards\LanguageTag\LanguageTag;
use PrinsFrank\Standards\Scripts\ScriptAlias;
use PrinsFrank\Standards\Scripts\ScriptName;
use PrinsFrank\TransliteratorWrapper\Enum\TransliterationDirection;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\BasicID;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\Filter;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\SpecialTag;
use PrinsFrank\TransliteratorWrapper\FormalIdSyntax\Components\Variant;
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

    public function convertScriptLanguage(
        ScriptName|ScriptAlias|LanguageTag|SpecialTag $source,
        ScriptName|ScriptAlias|LanguageTag|SpecialTag $target,
        Variant|null $variant = null,
        Filter|null $filter = null,
    ): static {
        $this->addSingleID(new SingleID(new BasicID($target, $source, $variant), $filter));

        return $this;
    }

    public function convertToScriptLanguage(
        ScriptName|ScriptAlias|LanguageTag|SpecialTag $target,
        Variant|null $variant = null,
        Filter|null $filter = null,
    ): static {
        $this->addSingleID(new SingleID(new BasicID($target, null, $variant), $filter));

        return $this;
    }

    public function toASCII(): static
    {
        return $this->convertScriptLanguage(SpecialTag::Any, ScriptName::Latin)
                    ->convertScriptLanguage(ScriptName::Latin, SpecialTag::ASCII);
    }

    public function toXSampa(): static
    {
        return $this->convertScriptLanguage(SpecialTag::IPA, SpecialTag::XSampa);
    }

    public function remove(Filter $filter): static
    {
        return $this->addSingleID(new SingleID(new BasicID(SpecialTag::Remove), $filter));
    }

    public function keep(Filter $filter): static
    {
        return $this->addSingleID(new SingleID(new BasicID(SpecialTag::Remove), $filter->inverse()));
    }

    public function getTransliterator(): Transliterator
    {
        if ($this->globalFilter === null && count($this->singleIDS) === 1) {
            $this->typedTransliterator->create($this->singleIDS[0], $this->direction);
        }

        return $this->typedTransliterator->create(new CompoundID($this->singleIDS, $this->globalFilter), $this->direction);
    }

    public function transliterate(string $string): string
    {
        if ($this->globalFilter === null && count($this->singleIDS) === 1) {
            $this->typedTransliterator->transliterate($string, $this->singleIDS[0], $this->direction);
        }

        return $this->typedTransliterator->transliterate($string, new CompoundID($this->singleIDS, $this->globalFilter), $this->direction);
    }
}
