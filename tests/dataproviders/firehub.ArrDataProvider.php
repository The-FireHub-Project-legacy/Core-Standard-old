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

/**
 * ### Array data provider
 * @since 1.0.0
 */
final class ArrDataProvider {

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function mixed ():array {

        return [
            [[]],
            [[1, 2, 3]],
            [['one' => 1, 'two' => 2, 'three' => 3]]
        ];

    }

}