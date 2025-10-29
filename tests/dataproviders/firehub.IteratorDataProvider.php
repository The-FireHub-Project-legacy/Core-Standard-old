<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @package Core\Test
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Tests\DataProviders;

use FireHub\Core\Testing\Base;
use Countable, IteratorAggregate, Traversable;

/**
 * ### Iterables data provider
 * @since 1.0.0
 */
final class IteratorDataProvider extends Base {

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function listArray ():array {

        return [
            [[], 0],
            [[1, 2, 3], 3],
            [[null, false, true], 3]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function associativeArray ():array {

        return [
            [['one' => 1, 'two' => 2, 'three' => 3], 3]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function multidimensionalArray ():array {

        return [
            [['one' => [1, 2, 3], 'two' => [4, 5, 6], 'three' => [7, 8, 9]], 3]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function iterator ():array {

        return [
            [new class implements Countable, IteratorAggregate {
                public array $data = [1, 2, 3];
                public function count ():int {return 3;}
                public function getIterator ():Traversable {yield from $this->data;}
            }, 3]
        ];

    }

}