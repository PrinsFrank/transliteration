<?php
declare(strict_types=1);

namespace PrinsFrank\Transliteration\Exception;

/** @api */
class UnableToCreateTransliteratorException extends TransliterationException
{
    public function __construct(string $message, ?string $id)
    {
        if ($id === null) {
            parent::__construct($message);

            return;
        }

        parent::__construct($message . ' with id "' . $id . '"');
    }
}
