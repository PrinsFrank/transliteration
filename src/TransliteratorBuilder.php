<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration;

use PrinsFrank\Standards\LanguageTag\LanguageTag;
use PrinsFrank\Standards\Scripts\ScriptAlias;
use PrinsFrank\Standards\Scripts\ScriptName;
use PrinsFrank\Transliteration\Enum\TransliterationDirection;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\BasicID;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Filter;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\SpecialTag;
use PrinsFrank\Transliteration\FormalIdSyntax\Components\Variant;
use PrinsFrank\Transliteration\FormalIdSyntax\CompoundID;
use PrinsFrank\Transliteration\FormalIdSyntax\SingleID;
use PrinsFrank\Transliteration\Rule\Components\Conversion;
use PrinsFrank\Transliteration\Rule\Components\VariableDefinition;
use PrinsFrank\Transliteration\Rule\RuleList;
use Transliterator;

class TransliteratorBuilder
{
    /** @var list<SingleID|Conversion|VariableDefinition> */
    private array $conversions = [];

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
        $this->conversions[] = $singleID;

        return $this;
    }

    public function addConversion(Conversion $conversion): static
    {
        $this->conversions[] = $conversion;

        return $this;
    }

    public function addVariableDefinition(VariableDefinition $variableDefinition): static
    {
        $this->conversions[] = $variableDefinition;

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

    public function toIPA(): static
    {
        return $this->convertScriptLanguage(SpecialTag::XSampa, SpecialTag::IPA);
    }

    public function remove(Filter $filter): static
    {
        return $this->addSingleID(new SingleID(new BasicID(SpecialTag::Remove), $filter));
    }

    public function keep(Filter $filter): static
    {
        return $this->addSingleID(new SingleID(new BasicID(SpecialTag::Remove), $filter->inverse()));
    }

    public function replace(string $string, string $with): static
    {
        return $this->addConversion(new Conversion($string, $with));
    }

    public function getTransliterator(): Transliterator
    {
        if ($this->containsRuleComponent() === true) {
            return $this->typedTransliterator->create(new RuleList($this->globalFilter, $this->conversions), $this->direction);
        }

        if ($this->globalFilter === null && count($this->conversions) === 1) {
            return $this->typedTransliterator->create($this->conversions[0], $this->direction);
        }

        return $this->typedTransliterator->create(new CompoundID($this->conversions, $this->globalFilter), $this->direction);
    }

    public function transliterate(string $string): string
    {
        if ($this->containsRuleComponent() === true) {
            return $this->typedTransliterator->transliterate($string, new RuleList($this->globalFilter, $this->conversions), $this->direction);
        }

        if ($this->globalFilter === null && count($this->conversions) === 1) {
            return $this->typedTransliterator->transliterate($string, $this->conversions[0], $this->direction);
        }

        return $this->typedTransliterator->transliterate($string, new CompoundID($this->conversions, $this->globalFilter), $this->direction);
    }

    public function containsRuleComponent(): bool
    {
        foreach ($this->conversions as $conversion) {
            if ($conversion instanceof Conversion || $conversion instanceof VariableDefinition) {
                return true;
            }
        }

        return false;
    }
}
