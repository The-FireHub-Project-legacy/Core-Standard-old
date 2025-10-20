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

namespace FireHub\Core\Initializers\Autoload\Loaders;

use FireHub\Core\Initializers\Autoload\Loader;

use const DIRECTORY_SEPARATOR;

use function array_pop;
use function array_key_last;
use function array_shift;
use function explode;
use function implode;
use function is_file;
use function strtolower;

/**
 * Preloader for finding a class path for main classes – before the main autoloader is hit
 * @since 1.0.0
 */
final class Preloader implements Loader {

    /**
     * ### Home folder
     * @since 1.0.0
     *
     * @var string
     */
    private const string HOME_FOLDER = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @param string $class_prefix [optional] <p>
     * Filename prefix.
     * </p>
     *
     * @return void
     */
    public function __construct (
        private readonly string $class_prefix = ''
    ) {}

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Initializers\Autoload\Loaders\Preloader::requireFile() To include a file.
     * @uses \FireHub\Core\Initializers\Autoload\Loaders\Preloader::HOME_FOLDER As home folder.
     */
    public function __invoke (string $class):void {

        $class_components = explode('\\', $class);
        array_shift($class_components);
        array_shift($class_components);

        $classname = $class_components[array_key_last($class_components)] ?? '';
        array_pop($class_components);

        foreach ($class_components as $key => $value) $class_components[$key] = strtolower($value);
        $namespace = implode(DIRECTORY_SEPARATOR, $class_components);

        $file = self::HOME_FOLDER.$namespace.DIRECTORY_SEPARATOR.$this->class_prefix.$classname.'.php';

        if (is_file($file)) require $file;

    }

}