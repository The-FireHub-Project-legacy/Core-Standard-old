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
use FireHub\Core\Support\Exceptions\PHP\ {
    FailedToSetConfigurationOption, NotValidConfigurationOptionException, NotValidExtensionException
};
use FireHub\Core\Support\LowLevel\PHP;
use PHPUnit\Framework\Attributes\ {
    CoversClass, Depends, Group, Small, TestWith
};

/**
 * ### Test php low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(PHP::class)]
final class PHPTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param string $name
     *
     * @return void
     */
    #[TestWith(['Core'])]
    public function testIsExtensionLoaded (string $name):void {

        $this->assertTrue(PHP::isExtensionLoaded($name));

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testLoadedExtensions ():void {

        $this->assertIsList(PHP::loadedExtensions());

    }

    /**
     * @since 1.0.0
     *
     * @param string $name
     *
     * @throws \FireHub\Core\Support\Exceptions\PHP\NotValidExtensionException
     *
     * @return void
     */
    #[TestWith(['Core'])]
    public function testIsExtensionFunctions (string $name):void {

        $this->assertIsList(PHP::extensionFunctions($name));

    }

    /**
     * @since 1.0.0
     *
     * @param string $name
     *
     * @return void
     */
    #[TestWith(['NotValidExtension'])]
    public function testIsExtensionFunctionsNotValid (string $name):void {

        $this->expectException(NotValidExtensionException::class);

        PHP::extensionFunctions($name);

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testScriptOwner ():void {

        $this->assertIsString(PHP::scriptOwner());

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testIncludedFiles ():void {

        $this->assertIsList(PHP::includedFiles());

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $assignment
     *
     * @return void
     */
    #[TestWith(['FOO=BAR'])]
    public function testSetEnvironmentVariables (string $assignment):void {

        $this->assertTrue(PHP::setEnvironmentVariable($assignment));

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testGetEnvironmentVariables ():void {

        $this->assertIsArray(PHP::getEnvironmentVariable());

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $name
     *
     * @return void
     */
    #[TestWith(['FOO'])]
    #[Depends('testSetEnvironmentVariables')]
    public function testGetEnvironmentVariablesWithName (string $name):void {

        $this->assertSame('BAR', PHP::getEnvironmentVariable($name));

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testGetConfigurationPath ():void {

        $this->assertIsString(PHP::getConfigurationPath());

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $option
     *
     * @throws \FireHub\Core\Support\Exceptions\PHP\NotValidConfigurationOptionException
     *
     * @return void
     */
    #[TestWith(['post_max_size'])]
    public function testGetConfigurationOption (string $option):void {

        $this->assertIsString(PHP::getConfigurationOption($option));

    }

    /**
     * @since 1.0.0
     *
     * @param string $name
     *
     * @return void
     */
    #[TestWith(['NotValidConfigurationOption'])]
    public function testGetConfigurationOptionNotValid (string $name):void {

        $this->expectException(NotValidConfigurationOptionException::class);

        PHP::getConfigurationOption($name);

    }

    /**
     * @since 1.0.0
     *
     * @throws \FireHub\Core\Support\Exceptions\PHP\NotValidExtensionException
     *
     * @return void
     */
    public function testGetConfigurationOptions ():void {

        $this->assertIsArray(PHP::getConfigurationOptions());

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $extension
     *
     * @throws \FireHub\Core\Support\Exceptions\PHP\NotValidExtensionException
     *
     * @return void
     */
    #[TestWith(['pcre'])]
    public function testGetConfigurationOptionWithName (string $extension):void {

        $this->assertIsArray(PHP::getConfigurationOptions($extension));

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $extension
     *
     * @return void
     */
    #[TestWith(['NotValidExtension'])]
    public function testGetConfigurationOptionWithNameNotValid (string $extension):void {

        $this->expectException(NotValidExtensionException::class);

        PHP::getConfigurationOptions($extension);

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $option
     * @param null|scalar $value
     *
     * @throws \FireHub\Core\Support\Exceptions\PHP\FailedToSetConfigurationOption
     *
     * @return void
     */
    #[TestWith(['display_errors', '0'])]
    public function testSetConfigurationOptions (string $option, null|int|float|string|bool $value):void {

        $this->assertTrue(PHP::setConfigurationOption($option, $value));

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $option
     * @param null|scalar $value
     *
     * @return void
     */
    #[TestWith(['test', '0'])]
    public function testSetConfigurationOptionsFailed (string $option, null|int|float|string|bool $value):void {

        $this->expectException(FailedToSetConfigurationOption::class);

        PHP::setConfigurationOption($option, $value);

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $option
     *
     * @return void
     */
    #[TestWith(['display_errors'])]
    public function testRestoreConfigurationOption (string $option):void {

        PHP::restoreConfigurationOption($option);

        $this->assertTrue(true);

    }

    /**
     * @since 1.0.0
     *
     * @param int $expected
     * @param non-empty-string $actual
     *
     * @return void
     */
    #[TestWith([1024, '1024'])]
    #[TestWith([1073741824, '1024M'])]
    #[TestWith([524288, '512K'])]
    #[TestWith([261120, '0xFFk'])]
    #[TestWith([10240, '0b1010k'])]
    #[TestWith([532, '0o1024'])]
    #[TestWith([532, '01024'])]
    public function testParseConfigurationQuantity (int $expected, string $actual):void {

        $this->assertSame($expected, PHP::parseConfigurationQuantity($actual));

    }

    /**
     * @since 1.0.0
     *
     * @throws \FireHub\Core\Support\Exceptions\PHP\FailedToGetProcessIDException
     *
     * @return void
     */
    public function testProcessID ():void {

        $this->assertIsInt(PHP::processID());

    }

    /**
     * @since 1.0.0
     *
     * @throws \FireHub\Core\Support\Exceptions\PHP\FailedToGetServerAPIException
     *
     * @return void
     */
    public function testServerAPI ():void {

        $this->assertIsString(PHP::serverAPI());

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $key
     *
     * @return void
     */
    #[TestWith(['name'])]
    #[TestWith(['hostname'])]
    #[TestWith(['release'])]
    #[TestWith(['version'])]
    #[TestWith(['machine'])]
    public function testOsInfo (string $key):void {

        $this->assertArrayHasKey($key, PHP::osInfo());

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testVersion ():void {

        $this->assertSame(PHP::version(), PHP::version('Core'));

    }

    /**
     * @since 1.0.0
     *
     * @param int-mask<-1, 0, 1> $result
     * @param string $first
     * @param string $second
     *
     * @return void
     */
    #[TestWith([-1, '1.0.0', '1.0.1'])]
    public function testCompareVersion (int $result, string $first, string $second):void {

        $this->assertSame($result, PHP::compareVersion($first, $second));

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testZendVersion ():void {

        $this->assertIsString(PHP::zendVersion());

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testTempFolder ():void {

        $this->assertIsString(PHP::tempFolder());

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testGetMemoryUsage ():void {

        $this->assertIsInt(PHP::getMemoryUsage());
        $this->assertTrue(PHP::getMemoryUsage() > 0);

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testGetMemoryPeakUsage ():void {

        $this->assertIsInt(PHP::getMemoryPeakUsage());
        $this->assertTrue(PHP::getMemoryPeakUsage() > 0);

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    #[TestWith([1])]
    public function testResetMemoryPeakUsage ():void {

        PHP::resetMemoryPeakUsage();

        $this->assertTrue(true);

    }

    /**
     * @since 1.0.0
     *
     * @param non-negative-int $seconds
     *
     * @return void
     */
    #[TestWith([0])]
    public function testSleep (int $seconds):void {

        $this->assertTrue(PHP::sleep($seconds));

    }

    /**
     * @since 1.0.0
     *
     * @param int<0, 999999> $microseconds
     *
     * @return void
     */
    #[TestWith([1])]
    public function testMicrosleep (int $microseconds):void {

        PHP::microsleep($microseconds);

        $this->assertTrue(true);

    }

}