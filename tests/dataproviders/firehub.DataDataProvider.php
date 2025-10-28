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

use FireHub\Core\Support\Enums\Data\Type;
use Countable;

/**
 * ### Data data provider
 * @since 1.0.0
 */
final class DataDataProvider {

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function string ():array {

        return [
            ['test', Type::T_STRING],
            ['', Type::T_STRING]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function int ():array {

        return [
            [10, Type::T_INT],
            [-5, Type::T_INT],
            [0, Type::T_INT]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function float ():array {

        return [
            [10.5, Type::T_FLOAT],
            [-2.3, Type::T_FLOAT]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function array ():array {

        return [
            [[1, 2, 3], Type::T_ARRAY],
            [[1 => 'one', 2 => 'two', 3 => 'three'], Type::T_ARRAY]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function null ():array {

        return [
            [null, Type::T_NULL]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function bool ():array {

        return [
            [true, Type::T_BOOL],
            [false, Type::T_BOOL]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function callable ():array {

        return [
            [fn() => true, Type::T_OBJECT],
            [new class {public function __invoke () {}}, Type::T_OBJECT]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function countable ():array {

        return [
            [new class implements Countable {public function count ():int {return 10;}}, Type::T_OBJECT]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function resource ():array {

        return [
            [fopen('php://stdout', 'wb'), Type::T_RESOURCE]
        ];

    }

}