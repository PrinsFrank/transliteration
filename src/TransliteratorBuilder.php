<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration;

use PrinsFrank\Transliteration\Exception\RecursionException;
use PrinsFrank\Transliteration\Syntax\Enum\TransliterationDirection;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use PrinsFrank\Transliteration\Exception\UnableToCreateTransliteratorException;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Filter;
use PrinsFrank\Transliteration\Syntax\FormalId\CompoundID;
use PrinsFrank\Transliteration\Syntax\FormalId\SingleID;
use PrinsFrank\Transliteration\Syntax\Rule\Components\Conversion;
use PrinsFrank\Transliteration\Syntax\Rule\Components\VariableDefinition;
use PrinsFrank\Transliteration\Syntax\Rule\RuleList;
use PrinsFrank\Transliteration\Transliterator\RecursionHandler;
use PrinsFrank\Transliteration\Transliterator\TypedTransliterator;
use Transliterator;

/** @api */
class TransliteratorBuilder
{
    /** @var list<SingleID|Conversion|VariableDefinition> */
    protected array $conversions = [];

    protected TransliterationDirection $direction = TransliterationDirection::FORWARD;

    protected Filter|null $globalFilter = null;

    /** @internal to detect recursive ConversionSets before they hang a system */
    protected readonly RecursionHandler $recursionHandler;

    public function __construct()
    {
        $this->recursionHandler = new RecursionHandler($this);
    }

    public function setDirection(TransliterationDirection $direction): static
    {
        $this->direction = $direction;

        return $this;
    }

    public function getDirection(): TransliterationDirection
    {
        return $this->direction;
    }

    public function setGlobalFilter(Filter|null $globalFilter): static
    {
        $this->globalFilter = $globalFilter;

        return $this;
    }

    public function getGlobalFilter(): ?Filter
    {
        return $this->globalFilter;
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

    /** @return list<SingleID|Conversion|VariableDefinition> */
    public function getConversions(): array
    {
        return $this->conversions;
    }

    /** @throws RecursionException */
    public function applyConversionSet(ConversionSet $conversionSet): static
    {
        $this->recursionHandler->applyConversionSet($conversionSet);

        return $this;
    }

    /**
     * @param list<ConversionSet> $conversionSets
     * @throws RecursionException
     */
    public function applyConversionSets(array $conversionSets): static
    {
        foreach ($conversionSets as $conversionSet) {
            $this->applyConversionSet($conversionSet);
        }

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     * @throws UnableToCreateTransliteratorException
     */
    public function getTransliterator(): Transliterator
    {
        if ($this->conversions === []) {
            throw new UnableToCreateTransliteratorException('There are no conversions', null);
        }

        if ($this->containsRuleSyntax() === true) {
            return TypedTransliterator::create(new RuleList($this->conversions, $this->globalFilter), $this->direction);
        }

        if ($this->globalFilter === null && count($this->conversions) === 1) {
            return TypedTransliterator::create($this->conversions[0], $this->direction);
        }

        return TypedTransliterator::create(new CompoundID($this->conversions, $this->globalFilter), $this->direction);
    }

    /**
     * @throws InvalidArgumentException
     * @throws UnableToCreateTransliteratorException
     */
    public function transliterate(string $string): string
    {
        if ($this->conversions === []) {
            throw new UnableToCreateTransliteratorException('There are no conversions', null);
        }

        if ($this->containsRuleSyntax() === true) {
            return TypedTransliterator::transliterate($string, new RuleList($this->conversions, $this->globalFilter), $this->direction);
        }

        if ($this->globalFilter === null && count($this->conversions) === 1) {
            return TypedTransliterator::transliterate($string, $this->conversions[0], $this->direction);
        }

        return TypedTransliterator::transliterate($string, new CompoundID($this->conversions, $this->globalFilter), $this->direction);
    }

    /** @phpstan-assert-if-false list<SingleID> $this->conversions */
    protected function containsRuleSyntax(): bool
    {
        foreach ($this->conversions as $conversion) {
            if ($conversion instanceof SingleID === false) {
                return true;
            }
        }

        return false;
    }
}
