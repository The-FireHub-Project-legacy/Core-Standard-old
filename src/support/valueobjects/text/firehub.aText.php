<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 8.2
 * @package Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\ValueObjects\Text;

use FireHub\Core\Support\Contracts\HighLevel\ValueObjects;
use FireHub\Core\Support\Enums\String\Encoding;
use FireHub\Core\Support\LowLevel\StrMB;

/**
 * ### Text value object
 *
 * Class allows you to manipulate text in various ways.
 * @since 1.0.0
 *
 * @template TString of non-empty-string
 *
 * @implements \FireHub\Core\Support\Contracts\HighLevel\ValueObjects<TString>
 */
abstract readonly class aText implements ValueObjects {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     *
     * @param TString $string <p>
     * The string.
     * </p>
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * String encoding.
     * </p>
     *
     * @return void
     */
    public function __construct (
        protected string $string,
        protected ?Encoding $encoding = null
    ) {}


    // -------------------------------------------------------------------------
    // Basic
    // -------------------------------------------------------------------------


    /**
     * ### Get string encoding
     *
     * <code>
     * use FireHub\Core\Support\ValueObjects\Text\Char;
     *
     * $char = new Char('F');
     *
     * $char->encoding();
     *
     * // Encoding::UTF_8
     * </code>
     *
     * @since 1.0.0
     *
     * @throws \FireHub\Core\Support\Exceptions\EncodingException If we couldn't get current encoding.
     *
     * @return \FireHub\Core\Support\Enums\String\Encoding Character encoding.
     */
    public function encoding ():Encoding {

        return $this->encoding ?? StrMB::encoding();

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\ValueObjects\Text\Char;
     *
     * $char = new Char('F');
     *
     * $char->value();
     *
     * // F
     * </code>
     *
     * @since 1.0.0
     *
     * @return TString The string representation of the value.
     */
    public function value ():string {

        return $this->string;

    }


    // -------------------------------------------------------------------------
    // Type check
    // -------------------------------------------------------------------------


    /**
     * ### Checks if VO value equals value
     *
     * <code>
     * use FireHub\Core\Support\ValueObjects\Text\Char;
     *
     * $char = new Char('F');
     *
     * $char->equals('F');
     *
     * // true
     * </code>
     * You can use multiple values as well:
     * <code>
     * use FireHub\Core\Support\ValueObjects\Text\Char;
     *
     * $char = new Char('F');
     *
     * $char->equals('A', 'B', 'C'');
     *
     * // false
     * </code>
     *
     * @since 1.0.0
     *
     * @param non-empty-string ...$values <p>
     * Value to check to equality with VO value.
     * </p>
     *
     * @return bool True if VO value equals provided value, false otherwise.
     */
    public function equals (string ...$values):bool {

        foreach ($values as $value)
            if ($this->string === $value) return true;

        return false;

    }


    // -------------------------------------------------------------------------
    // Magic
    // -------------------------------------------------------------------------


    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\ValueObjects\Text\aText::value() As return.
     *
     * @return TString The value representation of the object.
     */
    public function __toString ():string {

        return $this->value();

    }

}