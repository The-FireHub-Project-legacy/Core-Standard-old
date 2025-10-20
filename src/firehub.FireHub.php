<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 7.4
 * @package Core
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core;

use FireHub\Core\Initializers\FireHubConfigurator;

/**
 * ### Main FireHub class for bootstrapping
 *
 * This class contains all system definitions, constants, and dependant components for FireHub bootstrapping.
 * @since 1.0.0
 */
final class FireHub {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Initializers\FireHubConfigurator To get FireHub configuration.
     *
     * @param \FireHub\Core\Initializers\FireHubConfigurator $configurator <p>
     * FireHub application configuration.
     * </p>
     *
     * @return void
     */
    public function __construct (
        private readonly FireHubConfigurator $configurator
    ) {}

    /**
     * ### Light the torch
     *
     * This methode serves for instantiating the FireHub framework.
     * @since 1.0.0
     *
     * @return string Response from Kernel.
     */
    public function boot ():string {

        return 'Hy!';

    }

}