<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration;

use PrinsFrank\Standards\LanguageTag\LanguageTag;
use PrinsFrank\Standards\Scripts\ScriptAlias;
use PrinsFrank\Standards\Scripts\ScriptName;
use PrinsFrank\Transliteration\Syntax\Enum\SpecialTag;
use PrinsFrank\Transliteration\Syntax\Enum\TransliterationDirection;
use PrinsFrank\Transliteration\Syntax\Enum\Variant;
use PrinsFrank\Transliteration\Exception\InvalidArgumentException;
use PrinsFrank\Transliteration\Exception\UnableToCreateTransliteratorException;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\BasicID;
use PrinsFrank\Transliteration\Syntax\FormalId\Components\Filter;
use PrinsFrank\Transliteration\Syntax\FormalId\CompoundID;
use PrinsFrank\Transliteration\Syntax\FormalId\SingleID;
use PrinsFrank\Transliteration\Syntax\Rule\Components\Conversion;
use PrinsFrank\Transliteration\Syntax\Rule\Components\VariableDefinition;
use PrinsFrank\Transliteration\Syntax\Rule\RuleList;
use PrinsFrank\Transliteration\Transliterator\TypedTransliterator;
use Transliterator;

/** @api */
class TransliteratorBuilder
{
    /** @var list<SingleID|Conversion|VariableDefinition> */
    protected array $conversions = [];

    protected TransliterationDirection $direction = TransliterationDirection::FORWARD;

    protected Filter|null $globalFilter = null;

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
        return $this->addSingleID(new SingleID(new BasicID(SpecialTag::Remove), $filter->getInverse()));
    }

    /**
     * @phpstan-assert non-empty-string $string
     *
     * @throws InvalidArgumentException
     */
    public function replace(string $string, string $with): static
    {
        return $this->addConversion(new Conversion($string, $with));
    }

    /** @throws InvalidArgumentException */
    public function IPAToEnglishApproximation(): static
    {
        return $this->replace('dʒ', 'g')
            ->replace('kʰ', 'c')
            ->replace('kʷ', 'qu')
            ->replace('kᶣ', 'cu')
            ->replace('ɫ', 'll')
            ->replace('ŋ', 'n')
            ->replace('Ŋ', 'N')
            ->replace('ɲ', 'n')
            ->replace('Ɲ', 'N')
            ->replace('pʰ', 'p')
            ->replace('ʃ', 'sh')
            ->replace('Ʃ', 'SH')
            ->replace('tʰ', 't')
            ->replace('tʃ', 'ch')
            ->replace('aː', 'a')
            ->replace('Aː', 'A')
            ->replace('ɛ', 'e')
            ->replace('Ɛ', 'E')
            ->replace('eː', 'a')
            ->replace('Eː', 'A')
            ->replace('ɪ', 'i')
            ->replace('Ɪ', 'I')
            ->replace('iː', 'i')
            ->replace('Iː', 'I')
            ->replace('ɔ', 'o')
            ->replace('Ɔ', 'O')
            ->replace('oː', 'aw')
            ->replace('ʊ', 'u')
            ->replace('Ʊ', 'U')
            ->replace('ʌ', 'u')
            ->replace('Ʌ', 'U')
            ->replace('uː', 'u')
            ->replace('yː', 'u')
            ->replace('ae̯', 'igh')
            ->replace('oe̯', 'oy')
            ->replace('au̯', 'ow')
            ->replace('ei̯', 'ay')
            ->replace('ui̯', 'ui');
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
