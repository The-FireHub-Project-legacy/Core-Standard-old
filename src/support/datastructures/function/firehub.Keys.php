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

namespace FireHub\Core\Support\DataStructures\Function;

use FireHub\Core\Support\Contracts\HighLevel\DataStructures;
use FireHub\Core\Support\DataStructures\Linear\Indexed;

/**
 * ### Get keys from the data structure
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\Contracts\HighLevel\DataStructures
 */
readonly class Keys {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Contracts\HighLevel\DataStructures As parameter.
     *
     * @param TDataStructure $data_structure <p>
     * Instance of data structure.
     * </p>
     *
     * @return void
     */
    public function __construct (
        protected DataStructures $data_structure
    ) {}

    /**
     * ### Call an object as a function
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Function\Keys;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $keys = new Keys($collection);
     *
     * // ['firstname', 'lastname', 'age', 10]
     * </code>
     * You can use function to filter keys based on a callback result:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Function\Keys;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $keys = new Keys($collection)fn($value, $key) => $value !== 25);
     *
     * // ['firstname', 'lastname', 10]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Indexed As return.
     *
     * @param null|callable(value-of<TDataStructure>, key-of<TDataStructure>):bool $callback [optional] <p>
     * If specified, then only keys where user function is true are returned.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Indexed<key-of<TDataStructure>> Keys from the data structure.
     */
    public function __invoke (?callable $callback = null):Indexed {

        $array = [];

        if (!isset($callback))
            foreach ($this->data_structure as $key => $value) $array[] = $key;
        else foreach ($this->data_structure as $key => $value)
            if ($callback($value, $key)) $array[] = $key;

        return new Indexed($array);

    }

}