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
use FireHub\Core\Support\Enums\String\Encoding;
use FireHub\Core\Support\LowLevel\RegexMB;
use PHPUnit\Framework\Attributes\ {
    CoversClass, Depends, Group, Small, TestWith
};

/**
 * ### Test multibyte regex low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(RegexMB::class)]
final class RegexMBTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param bool $expected
     * @param string $actual
     * @param string $pattern
     * @param bool $case_sensitive
     * @param list<string> $result_out
     *
     * @return void
     */
    #[TestWith([true, 'Danži is a boy.', 'danži', false, [0 => 'Danži']])]
    #[TestWith([false, 'Danži is a boy.', 'danži', true, []])]
    public function testMatch (bool $expected, string $actual, string $pattern, bool $case_sensitive, ?array $result_out):void {

        $this->assertSame($expected, RegexMB::match($pattern, $actual, $case_sensitive, $result));
        $this->assertSame($result_out, $result);

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param string $pattern
     * @param string $replacement
     * @param bool $case_sensitive
     *
     * @throws \FireHub\Core\Support\Exceptions\Regex\ReplaceException
     *
     * @return void
     */
    #[TestWith(['PhP, ça marche !', 'éhé, ça marche !', '[é]', 'P', true])]
    #[TestWith(['PhP, ça marche !', 'Php, ça marche !', '[P]', 'P', false])]
    public function testReplace (string $expected, string $actual, string $pattern, string $replacement, bool $case_sensitive):void {

        $this->assertSame($expected, RegexMB::replace($pattern, $replacement, $actual, $case_sensitive));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param string $pattern
     *
     * @throws \FireHub\Core\Support\Exceptions\Regex\ReplaceException
     *
     * @return void
     */
    #[TestWith([
        'April fools day is 04/01/2003 and last christmas was 12/24/2002.',
        'April fools day is 04/01/2002 and last christmas was 12/24/2001.',
        '(\d{2}/\d{2}/)(\d{4})'
    ])]
    public function testReplaceFunc (string $expected, string $actual, string $pattern):void {

        $this->assertSame(
            $expected,
            RegexMB::replaceFunc(
                $pattern,
                static fn($matches) => $matches[1].($matches[2]+1),
                $actual
            )
        );

    }

    /**
     * @since 1.0.0
     *
     * @param list<string> $expected
     * @param string $actual
     * @param string $pattern
     * @param int $limit
     *
     * @throws \FireHub\Core\Support\Exceptions\Regex\SplitException
     *
     * @return void
     */
    #[TestWith([['', 'h', ''], 'éhé', 'é', -1])]
    public function testSplit (array $expected, string $actual, string $pattern, int $limit):void {

        $this->assertSame($expected, RegexMB::split($pattern, $actual, $limit));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding
     *
     * @throws \FireHub\Core\Support\Exceptions\EncodingException
     *
     * @return void
     */
    #[TestWith([Encoding::UTF_8])]
    public function testSetEncoding (Encoding $encoding):void {

        $this->assertTrue(RegexMB::encoding($encoding));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Enums\String\Encoding $encoding
     *
     * @throws \FireHub\Core\Support\Exceptions\EncodingException
     *
     * @return void
     */
    #[TestWith([Encoding::UTF_8])]
    #[Depends('testSetEncoding')]
    public function testGetEncoding (Encoding $encoding):void {

        $this->assertSame($encoding, RegexMB::encoding());

    }

}