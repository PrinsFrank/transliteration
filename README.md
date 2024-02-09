<picture>
    <source srcset="https://github.com/PrinsFrank/transliteration/raw/main/.github/images/banner_dark.png" media="(prefers-color-scheme: dark)">
    <img src="https://github.com/PrinsFrank/transliteration/raw/main/.github/images/banner_light.png" alt="Banner">
</picture>

# Transliteration

![GitHub](https://img.shields.io/github/license/prinsfrank/transliteration)
![PHP Version Support](https://img.shields.io/packagist/php-v/prinsfrank/transliteration)
[![codecov](https://codecov.io/gh/PrinsFrank/transliteration/branch/main/graph/badge.svg?token=9O3VB563MU)](https://codecov.io/gh/PrinsFrank/transliteration)
![PHPStan Level](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg?style=flat)

**A typed wrapper for the native PHP transliterator offering typed and documented arguments instead of an arbitrary string**

## Setup

> **Note**
> Make sure you are running PHP 8.1 or higher to use this package

To start right away, run the following command in your composer project;

```composer require prinsfrank/transliteration```

Or for development only;

```composer require prinsfrank/transliteration --dev```

## Why this package?

Transliteration is to represent or spell in the characters of another alphabet. But Transliteration in PHP is so much more powerful than that. It's a shame that it is [barely documented](https://www.php.net/manual/en/transliterator.create.php) and that you'll have to look in the [ICU documentation](https://unicode-org.github.io/icu/userguide/transforms/general/) for a full feature list.

But worst of all: the arguments for transliterator creation are strings. This package provides a full strictly-typed wrapper to create transliterators. It also provides some ready-to-go ConversionSets to get you up and running in no time!

## Use Cases

### Personal communication

Let's say you have a platform (shop, SAAS etc.) in many languages, but Customer Support staff speaks only a few of them. They have the platform available in their local language(s), but you can't translate things like names or addresses.

So a customer calls, and they have put in their name as `이준`. How do you pronounce this as someone that doesn't speak Korean but only English? Simple: You transliterate it and display it next to their name: `이준 (Ijun)`

### Identity verification

Another example: According to local laws and regulations you have to verify the identity of users on your platform (Banks, Employers etc.). They have entered their official name as `Ivan`, but their identity document says their name is `Иван`. As a person that doesn't read Cyrillic this might be hard to tell, but these in fact are the same. It might make sense here to display a name in multiple scripts depending on the country of the identity document.

## Transliteration basics

The transliteratorBuilder is the main entrypoint in this package. It provides a Generic but strictly-typed interface to the ext-intl transliterator. 

### Simple Conversions

The easiest example might be to replace one letter with some others, for example in German:

```php
(new TransliteratorBuilder())
    ->addConversion(new Conversion('ß', 'ss'))
    ->transliterate('Straße'); // Strasse 
```

Behind the scenes, this does eventually get converted to a string that is used to construct a rule based ext-intl transliterator; `'ß>ss;'`

In this example it's not clear where this package shines, so let's move on to something more challenging.

### Script conversions

Let's say we have a bunch of texts in arbitrary scripts, and we want to make sure that they can all be represented in ASCII.

```php
(new TransliteratorBuilder())
    ->applyConversionSet(new ToASCII())
    ->transliterate('アマゾン'); // amazon
```

As there is no direct transliteration from Any to ASCII possible, this results in the following conversion behind the scenes: `'Any-Latin;Latin-ASCII;'`

### IPA to English approximation

Now we're going one step further: Somewhere along the line one of our conversions results in an IPA character instead of English one. What if we could also approximate that in English? This package has you covered!

```php
(new TransliteratorBuilder())
    ->applyConversionSet(new ToASCII())
    ->applyConversionSet(new IPAToEnglishApproximation())
    ->transliterate('naɕi gʌba'); // naci guba
```

Behind the scenes the following ruleSet string gets created for you: 
`::Any-Latin;::Latin-ASCII;dʒ>g;kʰ>c;kʷ>qu;kᶣ>cu;ɫ>ll;ŋ>n;Ŋ>N;ɲ>n;Ɲ>N;pʰ>p;ʃ>sh;Ʃ>SH;tʰ>t;tʃ>ch;aː>a;Aː>A;ɛ>e;Ɛ>E;eː>a;Eː>A;ɪ>i;Ɪ>I;iː>i;Iː>I;ɔ>o;Ɔ>O;oː>aw;ʊ>u;Ʊ>U;ʌ>u;Ʌ>U;uː>u;yː>u;ae̯>igh;oe̯>oy;au̯>ow;ei̯>ay;ui̯>ui;`

Let's ignore the resulting ruleSet strings for the rest of this documentation, as the goal of this package is to provide you an abstract but typed interface for the transliteration package, without requiring you to understand the underlying syntax.

## Conversion sets

Conversion sets are the biggest building blocks in this package. They can be applied by calling the `applyConversionSet` method for each set;

```php
(new TransliteratorBuilder())
    ->applyConversionSet(new ToASCII())
    ->applyConversionSet(new IPAToEnglishApproximation())
```

Or calling the `applyConversionSets` method with an array of conversion sets;

```php
(new TransliteratorBuilder())
    ->applyConversionSets([
        new ToASCII(),
        new IPAToEnglishApproximation(),
    ])
```

### Bundled conversion sets

There are a bunch of conversion sets included in this package:

| Name                                                                                                                                 | Inverse     | Description                                                                                             |
|--------------------------------------------------------------------------------------------------------------------------------------|-------------|---------------------------------------------------------------------------------------------------------|
| [IPAToEnglishApproximation](https://github.com/PrinsFrank/transliteration/blob/main/src/ConversionSet/IPAToEnglishApproximation.php) |             | Replaces IPA characters in a string with an approximation of how the sounds would be written in English |
| [IPAToXSampa](https://github.com/PrinsFrank/transliteration/blob/main/src/ConversionSet/IPAToXSampa.php)                             | XSampaToIPA | Converts IPA notation to XSampa notation                                                                |
| [Keep](https://github.com/PrinsFrank/transliteration/blob/main/src/ConversionSet/Keep.php)                                           | Remove      | Keep only the characters or range(s) of characters set by the filter provided                           |
| [Remove](https://github.com/PrinsFrank/transliteration/blob/main/src/ConversionSet/Remove.php)                                       | Keep        | Remove all the characters or range(s) of characters set by the filter provided                          |
| [Replace](https://github.com/PrinsFrank/transliteration/blob/main/src/ConversionSet/Replace.php)                                     |             | Replace a string with another string                                                                    |
| [ReplaceAll](https://github.com/PrinsFrank/transliteration/blob/main/src/ConversionSet/ReplaceAll.php)                               |             | Replace a set of strings with another string                                                            |
| [ScriptLanguage](https://github.com/PrinsFrank/transliteration/blob/main/src/ConversionSet/ScriptLanguage.php)                       |             | Convert Between two scripts, languages or special tags                                                  |
| [ToASCII](https://github.com/PrinsFrank/transliteration/blob/main/src/ConversionSet/ToASCII.php)                                     |             | Convert to ASCII                                                                                        |
| [ToScriptLanguage](https://github.com/PrinsFrank/transliteration/blob/main/src/ConversionSet/ToScriptLanguage.php)                   |             | Convert to a script, language or special tag                                                            |
| [XSampaToIPA](https://github.com/PrinsFrank/transliteration/blob/main/src/ConversionSet/XSampaToIPA.php)                             | IPAToXSampa | Converts XSampa notation to IPA notation                                                                |

### Creating a custom conversion set

To create a custom conversion set, simply

1. Add the [ConversionSet interface](https://github.com/PrinsFrank/transliteration/blob/main/src/ConversionSet.php) to your class;

```php
use PrinsFrank\Transliteration\ConversionSet;

class CustomConversionSet implements ConversionSet
{
}
```

2. Add an implementation for the apply method;

```php
public function apply(TransliteratorBuilder $transliteratorBuilder): void
{
    // Add your code here
}
```

3. And pass your new custom conversion to the transliteratorBuilder;

```php
(new TransliteratorBuilder())
    ->applyConversionSet(new CustomConversionSet());
```

If the ConversionSet needs any arguments, you can implement a constructor and use those arguments in the apply method. For a simple example of this, see the [Replace](https://github.com/PrinsFrank/transliteration/blob/main/src/ConversionSet/Replace.php) ConversionSet.


