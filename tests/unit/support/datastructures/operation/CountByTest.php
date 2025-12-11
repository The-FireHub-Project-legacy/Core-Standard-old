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

namespace FireHub\Tests\Unit\Support\DataStructures\Linear;

use FireHub\Core\Testing\Base;
use FireHub\Tests\DataProviders\DataStructureDataProvider;
use FireHub\Core\Support\Contracts\HighLevel\DataStructures;
use FireHub\Core\Support\DataStructures\Linear\Indexed;
use FireHub\Core\Support\DataStructures\Operation\CountBy;
use FireHub\Core\Support\Enums\Data\Type;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Test CountBy data structure operation class
 * @since 1.0.0
 */
#[Small]
#[Group('datastructures')]
#[CoversClass(Indexed::class)]
#[CoversClass(CountBy::class)]
final class CountByTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $data_structure
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testValue (DataStructures $data_structure):void {

        $this->assertEquals(3, $data_structure->countBy()->value('Jane'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $data_structure
     *
     * @throws \FireHub\Core\Support\Exceptions\JSON\EncodingException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testValues (DataStructures $data_structure):void {

        $this->assertEquals(
            ['Jane' => 3, 'John' => 1, 'Richard' => 2],
            $data_structure->countBy()->values()->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $data_structure
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\TypeUnknownException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testTypes (DataStructures $data_structure):void {

        $this->assertEquals(
            2,
            $data_structure->countBy()->type(Type::T_STRING)
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $data_structure
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testWhere (DataStructures $data_structure):void {

        $this->assertEquals(
            ['J' => 4, 'R' => 2],
            $data_structure->countBy()->where(fn($value, $key) => substr((string)$value, 0, 1))->toArray()
        );

    }

}