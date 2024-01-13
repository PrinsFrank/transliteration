<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Exception;

class UnableToCreateTransliteratorException extends TransliterationException
{
    public function __construct(string $message, string $id)
    {
        parent::__construct($message . ' with id "' . $id . '"');
    }
}
