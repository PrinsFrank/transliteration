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

## Overview

> :mortar_board: **Transliteration** is _to represent or spell in the characters of another alphabet_.

But Transliteration in PHP is so much more powerful than that. It's a shame that it is [barely documented](https://www.php.net/manual/en/transliterator.create.php) and that you'll have to look in the [ICU documentation](https://unicode-org.github.io/icu/userguide/transforms/general/) for a full feature list. 
