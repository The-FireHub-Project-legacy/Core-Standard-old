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

namespace FireHub\Tests\Unit\Support\LowLevel;

use FireHub\Core\Testing\Base;
use FireHub\Tests\DataProviders\ConstantDataProvider;
use FireHub\Core\Support\Exceptions\Constant\NotDefinedException;
use FireHub\Core\Support\LowLevel\Constant;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Depends, Group, Small, TestWith
};

/**
 * ### Test constant low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(Constant::class)]
final class ConstantTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $name
     * @param null|array<array-key, mixed>|scalar $value
     *
     * @throws \FireHub\Core\Support\Exceptions\Constant\FailedToDefineException
     *
     * @return void
     */
    #[DataProviderExternal(ConstantDataProvider::class, 'types')]
    public function testDefine (string $name, null|array|bool|float|int|string $value):void {

        $this->assertTrue( Constant::define($name, $value));

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $name
     * @param null|array<array-key, mixed>|scalar $value
     *
     * @return void
     */
    #[DataProviderExternal(ConstantDataProvider::class, 'types')]
    #[Depends('testDefine')]
    public function testDefined (string $name, null|array|bool|float|int|string $value):void {

        $this->assertTrue( Constant::defined($name));

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $name
     * @param null|array<array-key, mixed>|scalar $value
     *
     * @throws \FireHub\Core\Support\Exceptions\Constant\NotDefinedException
     *
     * @return void
     */
    #[DataProviderExternal(ConstantDataProvider::class, 'types')]
    #[Depends('testDefine')]
    public function testValue (string $name, null|array|bool|float|int|string $value):void {

        $this->assertSame($value, Constant::value($name));

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $name
     *
     * @return void
     */
    #[TestWith(['NotDefined'])]
    #[Depends('testDefine')]
    public function testValueNotFound (string $name):void {

        $this->expectException(NotDefinedException::class);

        Constant::value($name);

    }

}