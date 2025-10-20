<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 8.3
 * @package Core\Initializers
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Initializers;

use FireHub\Core\Initializers\Autoload\Loaders\Preloader;
use FireHub\Core\FireHub;

use const DIRECTORY_SEPARATOR;

/**
 * ### FireHub application configuration
 * @since 1.0.0
 */
final class FireHubConfigurator {

    /**
     * ### Home FireHub folder
     * @since 1.0.0
     *
     * @var string
     */
    private const string HOME_FOLDER = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;

    /**
     * ### Files needed to be preloaded before autoload is hit
     * @since 1.0.0
     *
     * @var list<string>
     */
    private array $preloaders = [
        __DIR__.DIRECTORY_SEPARATOR.'firehub.Autoload.php',
        __DIR__.DIRECTORY_SEPARATOR.'autoload'.DIRECTORY_SEPARATOR.'firehub.Loader.php',
        __DIR__.DIRECTORY_SEPARATOR.'autoload'.DIRECTORY_SEPARATOR.'loaders'.DIRECTORY_SEPARATOR.'firehub.Preloader.php',
        self::HOME_FOLDER.'support'.DIRECTORY_SEPARATOR.'lowlevel'.DIRECTORY_SEPARATOR.'firehub.SplAutoload.php'
    ];

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @param string $app_path <p>
     * Base path of your application.
     * </p>
     *
     * @uses \FireHub\Core\Initializers\Autoload::prepend() To register a new autoloaded implementation
     * at the beginning of the queue.
     * @uses \FireHub\Core\Initializers\Autoload\Loaders\Preloader As preloader for finding a class path for main
     * classes – before the main autoloader is hit.
     *
     * @throws \FireHub\Core\Support\Exceptions\Autoload\RegisterAutoloaderException If failed to register a callback
     * function as an autoloader.
     *
     * @return void
     */
    public function __construct (
        private(set) readonly string $app_path
    ) {

        foreach ($this->preloaders as $preloader) require $preloader;

        Autoload::prepend(new Preloader('firehub.'));

    }

    /**
     * ### Create FireHub application
     * @since 1.0.0
     *
     * @uses \FireHub\Core\FireHub As return.
     *
     * @return \FireHub\Core\FireHub New Firehub Framework application.
     */
    public function create ():FireHub {

        return new FireHub($this);

    }

}