<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 7.0
 * @package Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\ValueObjects\Text;

use FireHub\Core\Support\Enums\String\Encoding;
use FireHub\Core\Support\Exceptions\Char\CharacterLengthException;
use FireHub\Core\Support\LowLevel\StrMB;

/**
 * ### Character value object
 *
 * Class allows you to manipulate characters in various ways.
 * @since 1.0.0
 *
 * @template TCharacter of non-empty-string
 *
 * @extends \FireHub\Core\Support\ValueObjects\Text\aText<TCharacter>
 */
final readonly class Char extends aText {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\String\Encoding As parameter.
     * @uses \FireHub\Core\Support\LowLevel\StrMB::length() To check the length of the character.
     *
     * @param TCharacter $character <p>
     * The character.
     * </p>
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding [optional] <p>
     * Character encoding.
     * </p>
     *
     * @throws \FireHub\Core\Support\Exceptions\Char\CharacterLengthException If the character is not exactly one
     * letter long.
     *
     * @return void
     */
    public function __construct (
        string $character,
        ?Encoding $encoding = null
    ) {

        if (($length = StrMB::length($character)) !== 1)
            throw new CharacterLengthException()
                ->withLength($length)
                ->withMessage('Character must be exactly one letter long.');

        parent::__construct($character, $encoding);

    }


    // -------------------------------------------------------------------------
    // Basic
    // -------------------------------------------------------------------------


    /**
     * ### Create a new instance with a different encoding
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding <p>
     * Character encoding.
     * </p>
     *
     * @throws \FireHub\Core\Support\Exceptions\Char\CharacterLengthException If the character is not exactly one
     * letter long.
     *
     * @return self<TCharacter>
     */
    public function withEncoding (Encoding $encoding):self {

        return new self($this->string, $encoding);

    }

}