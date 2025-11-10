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
use FireHub\Tests\DataProviders\StringDataProvider;
use FireHub\Core\Support\Enums\Side;
use FireHub\Core\Support\Enums\String\Count\ {
    CharacterMode, WordFormat
};
use FireHub\Core\Support\Exceptions\DataException;
use FireHub\Core\Support\Exceptions\Str\ {
    ChunkLengthLessThanOneException, ComparePartException, CountWordsException, EmptySeparatorException,
    EmptyPadException
};
use FireHub\Core\Support\LowLevel\ {
    StrSafe, StrSB
};
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Depends, Group, Small, TestWith
};
use Stringable;

pest()
    ->group('lowlevel');

covers(StrSafe::class, StrSB::class);

it('contains returns correct result', function (bool $expected, string $actual, string $string) {
    expect(StrSB::contains($actual, $string))->toBe($expected);
})->with([
    [false, 'j', ''],
    [true, 'j', 'ijk']
]);

it('add and strip slashes works correctly', function (string $expected, string $actual, ?string $characters = null, bool $c_representation = false) {
    expect(StrSB::addSlashes($actual, $characters))->toBe($expected);
    expect(StrSB::stripSlashes($expected, $c_representation))->toBe($actual);
})->with([
    ["O\\\\\\'Reilly", "O\'Reilly"]
]);