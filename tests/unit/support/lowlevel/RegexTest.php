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
use FireHub\Core\Support\LowLevel\Regex;
use PHPUnit\Framework\Attributes\ {
    CoversClass, Group, Small, TestWith
};

/**
 * ### Test regex low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(Regex::class)]
final class RegexTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param bool $expected
     * @param string $actual
     * @param string $pattern
     * @param int $offset
     * @param bool $all
     * @param null|string[] $result_out
     *
     * @return void
     */
    #[TestWith([true, 'PHP is the web scripting language of choice.', '/php/i', 0, false, [0 => 'PHP']])]
    #[TestWith([false, 'PHP is the web scripting language of choice.', '/php/i', 10, false, []])]
    #[TestWith([true, 'PHP is the web scripting language of choice.', '/\bweb\b/i', 0, false, [0 => 'web']])]
    #[TestWith([true, 'FireHub Web App FireHub Web App', '/Web/', 0, true, [0 => [0 => 'Web', 1 => 'Web']]])]
    public function testMatch (bool $expected, string $actual, string $pattern, int $offset, bool $all, ?array $result_out):void {

        $this->assertSame($expected, Regex::match($pattern, $actual, $offset, $all, $result));
        $this->assertSame($result_out, $result);

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param string $pattern
     * @param string $replacement
     * @param int $limit
     *
     * @throws \FireHub\Core\Support\Exceptions\Regex\ReplaceException
     *
     * @return void
     */
    #[TestWith(['April1,2003', 'April 15, 2003', '/(\w+) (\d+), (\d+)/i', '${1}1,$3', -1])]
    public function testReplace (string $expected, string $actual, string $pattern, string $replacement, int $limit):void {

        $this->assertSame($expected, Regex::replace($pattern, $replacement, $actual, $limit));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param string $pattern
     * @param int $limit
     *
     * @throws \FireHub\Core\Support\Exceptions\Regex\ReplaceException
     *
     * @return void
     */
    #[TestWith([
        'April fools day is 04/01/2003 and last christmas was 12/24/2002.',
        'April fools day is 04/01/2002 and last christmas was 12/24/2001.',
        '|(\d{2}/\d{2}/)(\d{4})|',
        -1
    ])]
    public function testReplaceFunc (string $expected, string $actual, string $pattern, int $limit):void {

        $this->assertSame(
            $expected,
            Regex::replaceFunc($pattern, static fn($matches) => $matches[1].($matches[2]+1),
                $actual,
                $limit
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
     * @param bool $remove_empty
     *
     * @throws \FireHub\Core\Support\Exceptions\Regex\SplitException
     *
     * @return void
     */
    #[TestWith([['Fire', 'ub'], 'FireHub', '/H/', -1, false])]
    public function testSplit (array $expected, string $actual, string $pattern, int $limit, bool $remove_empty):void {

        $this->assertSame($expected, Regex::split($pattern, $actual, $limit, $remove_empty));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param null|string $delimiter
     *
     * @return void
     */
    #[TestWith(['Fire\Hub', 'FireHub', 'H'])]
    public function testQuote (string $expected, string $actual, ?string $delimiter):void {

        $this->assertSame($expected, Regex::quote($actual, $delimiter));

    }

}