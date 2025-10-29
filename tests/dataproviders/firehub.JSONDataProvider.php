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
use FireHub\Core\Support\Enums\JSON\Flags\ {
    Decode, Encode
};

/**
 * ### JSON data provider
 * @since 1.0.0
 */
final class JSONDataProvider extends Base {

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function encode ():array {

        return [
            ['firehub', '"firehub"'],
            [[1,2,3], '[1,2,3]'],
            [['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], '{"a":1,"b":2,"c":3,"d":4,"e":5}'],
            [12.0, '12'],
            [12.0, '12.0', Encode::PRESERVE_ZERO_FRACTION]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function decode ():array {

        return [
            ['firehub', '"firehub"'],
            [[1,2,3], '[1,2,3]'],
            [['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], '{"a":1,"b":2,"c":3,"d":4,"e":5}'],
            [12.0, '12.0'],
            [1.2345678901234567E+19, "12345678901234567890"],
            ['12345678901234567890', "12345678901234567890", Decode::BIGINT_AS_STRING]
        ];

    }

}