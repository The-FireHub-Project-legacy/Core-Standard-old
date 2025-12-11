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

namespace FireHub\Tests;

pest()
    ->group('smoke')
    ->in('smoke');

pest()
    ->group('datastructures')
    ->in('unit\support\datastructures');

pest()
    ->group('lowlevel')
    ->in('unit\support\lowlevel');

pest()
    ->group('utils')
    ->in('unit\support\utils');

pest()
    ->group('valueobjects')
    ->in('unit\support\valueobjects');

pest()
    ->group('components')
    ->in('unit\components');