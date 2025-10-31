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
use FireHub\Core\Support\Enums\String\ {
    CaseFolding, Encoding
};
use FireHub\Core\Support\Exceptions\Str\ChunkLengthLessThanOneException;
use FireHub\Core\Support\LowLevel\ {
    StrMB, StrSafe
};
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small, TestWith
};
use Stringable;

/**
 * ### Test multibyte string low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(StrSafe::class)]
#[CoversClass(StrMB::class)]
final class StrMBTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param bool $expected
     * @param string $actual
     * @param string $string
     *
     * @return void
     */
    #[TestWith([false, 'ß', ''])]
    #[TestWith([true, 'ß', 'ÈßÁ'])]
    public function testContains (bool $expected, string $actual, string $string):void {

        $this->assertSame($expected, StrMB::contains($actual, $string));

    }

    /**
     * @since 1.0.0
     *
     * @param bool $expected
     * @param string $actual
     * @param string $string
     *
     * @return void
     */
    #[TestWith([false, 'ß', ''])]
    #[TestWith([true, 'È', 'ÈßÁ'])]
    public function testStartsWith (bool $expected, string $actual, string $string):void {

        $this->assertSame($expected, StrMB::startsWith($actual, $string));

    }

    /**
     * @since 1.0.0
     *
     * @param bool $expected
     * @param string $actual
     * @param string $string
     *
     * @return void
     */
    #[TestWith([false, 'ß', ''])]
    #[TestWith([true, 'Á', 'ÈßÁ'])]
    public function testEndsWith (bool $expected, string $actual, string $string):void {

        $this->assertSame($expected, StrMB::endsWith($actual, $string));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     *
     * @return void
     */
    #[TestWith(["đščć\\\\\\'ž", "đščć\'ž"])]
    #[TestWith(["đščć\'ž", "đščć'ž"])]
    #[TestWith(["đščć\\\\\\\"ž", 'đščć\"ž'])]
    #[TestWith(["đščć\\\\ž", 'đščć\\ž'])]
    #[TestWith(["đščć\\\\ž", 'đščć\ž'])]
    public function testAddStripSlashes (string $expected, string $actual):void {

        $this->assertSame($expected, StrMB::addSlashes($actual));
        $this->assertSame($actual, StrMB::stripSlashes($expected));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param array<array-key, null|scalar|Stringable> $actual
     * @param string $separator
     *
     * @throws \FireHub\Core\Support\Exceptions\DataException
     * @throws \FireHub\Core\Support\Exceptions\Str\EmptySeparatorException
     *
     * @return void
     */
    #[TestWith(['đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', ['đščćž', '诶杰艾玛', 'ЛЙ', 'ÈßÁ', 'カタカナ'], ' '])]
    #[TestWith(['đščć 诶杰艾玛 ЛЙ ÈßÁ žタžナ', ['đščć 诶杰艾玛 ЛЙ ÈßÁ ', 'タ', 'ナ'], 'ž'])]
    public function testImplodeExplode (string $expected, array $actual, string $separator):void {

        $this->assertSame($expected, StrMB::implode($actual, $separator));
        $this->assertSame($actual, StrMB::explode($expected, $separator));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     *
     * @return void
     */
    #[TestWith(["đščćž\. đščćž\.", 'đščćž. đščćž.'])]
    public function testQuoteMeta (string $expected, string $actual):void {

        $this->assertSame($expected, StrMB::quoteMeta($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param int $times
     * @param string $separator
     *
     * @return void
     */
    #[TestWith(['đščćž. đščćž.đščćž. đščćž.', 'đščćž. đščćž.', 2, ''])]
    public function testRepeat (string $expected, string $actual, int $times, string $separator):void {

        $this->assertSame($expected, StrMB::repeat($actual, $times, $separator));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param null|string|array<int, string> $allowed_tags
     *
     * @return void
     */
    #[TestWith([
        'đščćž 诶杰艾玛. ÈßÁ カタカナ',
        '<p>đščćž 诶杰艾玛.</p><!-- Comment --> <a href="#fragment">ÈßÁ カタカナ</a>'
    ])]
    #[TestWith([
        '<p>đščćž 诶杰艾玛.</p> <a href="#fragment">ÈßÁ カタカナ</a>',
        '<p>đščćž 诶杰艾玛.</p><!-- Comment --> <a href="#fragment">ÈßÁ カタカナ</a>',
        ['p', 'a']
    ])]
    public function testStripTags (string $expected, string $actual, null|string|array $allowed_tags = null):void {

        $this->assertSame($expected, StrMB::stripTags($actual, $allowed_tags));

    }

    /**
     * @since 1.0.0
     *
     * @param int<-1, 1> $expected
     * @param string $string_1
     * @param string $string_2
     *
     * @return void
     */
    #[TestWith([-1, 'Č', 'č'])]
    #[TestWith([1, 'čao', 'Čao'])]
    #[TestWith([0, 'Čao', 'Čao'])]
    public function testCompare (int $expected, string $string_1, string $string_2):void {

        $this->assertSame($expected, StrMB::compare($string_1, $string_2));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param array<non-empty-string, string> $replace_pairs
     *
     * @return void
     */
    #[TestWith(['诶玛艾玛', '诶杰艾玛', ['杰' => '玛']])]
    public function testTranslate (string $expected, string $actual, array $replace_pairs):void {

        $this->assertSame($expected, StrMB::translate($actual, $replace_pairs));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param \FireHub\Core\Support\Enums\String\CaseFolding $case_folding
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding
     *
     * @return void
     */
    #[TestWith(['ĐŠČĆŽ 诶杰艾玛 ЛЙ ÈSSÁ カタカナ', 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', CaseFolding::UPPER])]
    #[TestWith(['đščćž 诶杰艾玛 лй èssá カタカナ', 'ĐŠČĆŽ 诶杰艾玛 ЛЙ ÈSSÁ カタカナ', CaseFolding::LOWER])]
    #[TestWith(['Đščćž 诶杰艾玛 Лй Èßá カタカナ', 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', CaseFolding::TITLE])]
    public function testConvert (string $expected, string $actual, CaseFolding $case_folding, ?Encoding $encoding = null):void {

        $this->assertSame($expected, StrMB::convert($actual, $case_folding, $encoding));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     *
     * @return void
     */
    #[TestWith(['Đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ'])]
    public function testCapitalize (string $expected, string $actual):void {

        $this->assertSame($expected, StrMB::capitalize($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     *
     * @return void
     */
    #[TestWith(['đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'Đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ'])]
    public function testDeCapitalize (string $expected, string $actual):void {

        $this->assertSame($expected, StrMB::deCapitalize($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param int $start
     * @param null|int $length
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding
     *
     * @return void
     */
    #[TestWith(['诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 6, null])]
    #[TestWith(['ЛЙ È', 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 11, 4])]
    #[TestWith(['カタカ', 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', -4, 3])]
    public function testPart (string $expected, string $actual, int $start, ?int $length, ?Encoding $encoding = null):void {

        $this->assertSame($expected, StrMB::part($actual, $start, $length, $encoding));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param string $find
     * @param bool $before_needle
     * @param bool $case_sensitive
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding
     *
     * @return void
     */
    #[TestWith(['诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', '诶杰艾玛', false, true])]
    #[TestWith(['đščćž 诶杰艾玛', 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', ' ЛЙ', true, true])]
    #[TestWith(['ЛЙ ÈßÁ カタカナ', 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'лй', false, false])]
    public function testFirstPart (string $expected, string $actual, string $find, bool $before_needle, bool $case_sensitive, ?Encoding $encoding = null):void {

        $this->assertSame($expected, StrMB::firstPart($find, $actual, $before_needle, $case_sensitive, $encoding));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param string $find
     * @param bool $before_needle
     * @param bool $case_sensitive
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding
     *
     * @return void
     */
    #[TestWith(['诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', '诶杰艾玛', false, true])]
    #[TestWith(['đščćž 诶杰艾玛', 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', ' ЛЙ', true, true])]
    #[TestWith(['čćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'Č', false, false])]
    public function testLastPart (string $expected, string $actual, string $find, bool $before_needle, bool $case_sensitive, ?Encoding $encoding = null):void {

        $this->assertSame($expected, StrMB::lastPart($find, $actual, $before_needle, $case_sensitive, $encoding));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param \FireHub\Core\Support\Enums\Side $side
     * @param string $characters
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding
     *
     * @return void
     */
    #[TestWith([
        "ÈßÁ カタカナЙÈßÁ カタカナ :) ...  \n\r",
        "\t\tÈßÁ カタカナЙÈßÁ カタカナ :) ...  \n\r",
        Side::LEFT,
        " \n\r\t\v\x00"
    ])]
    #[TestWith([
        "\t\tÈßÁ カタカナЙÈßÁ カタカナ :) ...",
        "\t\tÈßÁ カタカナЙÈßÁ カタカナ :) ...",
        Side::RIGHT,
        " \n\r\t\v\x00"
    ])]
    #[TestWith([
        "ÈßÁ カタカナЙÈßÁ カタカナ :) ...",
        "\t\tÈßÁ カタカナЙÈßÁ カタカナ :) ...  \n\r",
        Side::BOTH,
        " \n\r\t\v\x00"
    ])]
    public function testTrim (string $expected, string $actual, Side $side, string $characters, ?Encoding $encoding = null):void {

        $this->assertSame($expected, StrMB::trim($actual, $side, $characters, $encoding));

    }

    /**
     * @since 1.0.0
     *
     * @param list<non-empty-string> $expected
     * @param non-empty-string $actual
     * @param positive-int $length
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding
     *
     * @throws \FireHub\Core\Support\Exceptions\Str\ChunkLengthLessThanOneException
     *
     * @return void
     */
    #[TestWith([
        [
            0 => 'đščćž',
            1 => ' 诶杰艾玛',
            2 => ' ЛЙ È',
            3 => 'ßÁ カタ',
            4 => 'カナ'
        ],
        'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ',
        5
    ])]
    public function testSplit (array $expected, string $actual, int $length, ?Encoding $encoding = null):void {

        $this->assertSame($expected, StrMB::split($actual, $length, $encoding));

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $actual
     * @param positive-int $length
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding
     *
     * @return void
     */
    #[TestWith(['The lazy fox jumped over the fence', 0])]
    public function testSplitLengthLessThanOne (string $actual, int $length, ?Encoding $encoding = null):void {

        $this->expectException(ChunkLengthLessThanOneException::class);

        StrMB::split($actual, $length, $encoding);

    }

    /**
     * @since 1.0.0
     *
     * @param non-negative-int $expected
     * @param string $actual
     * @param string $search
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding
     *
     * @return void
     */
    #[TestWith([2, 'ЛЙ ÈßÁ ЛЙ ÈßÁ', 'ЛЙ'])]
    public function testPartCount (int $expected, string $actual, string $search, ?Encoding $encoding = null):void {

        $this->assertSame($expected, StrMB::partCount($actual, $search, $encoding));

    }

    /**
     * @since 1.0.0
     *
     * @param non-negative-int $expected
     * @param string $actual
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding
     *
     * @return void
     */
    #[TestWith([22, 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ'])]
    public function testLength (int $expected, string $actual, ?Encoding $encoding = null):void {

        $this->assertSame($expected, StrMB::length($actual, $encoding));

    }

    /**
     * @since 1.0.0
     *
     * @param non-negative-int|false $expected
     * @param string $actual
     * @param string $search
     * @param bool $case_sensitive
     * @param int $offset
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding
     *
     * @return void
     */
    #[TestWith([false, 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'лй', true, 0])]
    #[TestWith([11, 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'лй', false, 0])]
    #[TestWith([11, 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'ЛЙ', false, 9])]
    #[TestWith([0, 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'đ', true, 0])]
    #[TestWith([false, 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'ЛЙ', false, 20])]
    public function testFirstPosition (int|false $expected, string $actual, string $search, bool $case_sensitive, int $offset, ?Encoding $encoding = null):void {

        $this->assertSame($expected, StrMB::firstPosition($search, $actual, $case_sensitive, $offset, $encoding));

    }

    /**
     * @since 1.0.0
     *
     * @param non-negative-int|false $expected
     * @param string $actual
     * @param string $search
     * @param bool $case_sensitive
     * @param int $offset
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding
     *
     * @return void
     */
    #[TestWith([false, 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'лй', true, 0])]
    #[TestWith([11, 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'лй', false, 0])]
    #[TestWith([11, 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'ЛЙ', false, 9])]
    #[TestWith([0, 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'đ', true, 0])]
    #[TestWith([false, 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', 'ЛЙ', false, 20])]
    public function testLastPosition (int|false $expected, string $actual, string $search, bool $case_sensitive, int $offset, ?Encoding $encoding = null):void {

        $this->assertSame($expected, StrMB::lastPosition($search, $actual, $case_sensitive, $offset, $encoding));

    }

    /**
     * @since 1.0.0
     *
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding
     *
     * @throws \FireHub\Core\Support\Exceptions\EncodingException
     *
     * @return void
     */
    #[TestWith([Encoding::UTF_8])]
    public function testEncoding (?Encoding $encoding):void {

        $this->assertTrue(StrMB::encoding($encoding));

        $this->assertSame($encoding, StrMB::encoding());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Enums\String\Encoding $expected
     * @param string $actual
     *
     * @return void
     */
    #[TestWith([Encoding::UTF_8, 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ'])]
    public function testDetectEncoding (Encoding $expected, string $actual):void {

        $this->assertSame($expected, StrMB::detectEncoding($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param \FireHub\Core\Support\Enums\String\Encoding $to
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $from
     *
     * @throws \FireHub\Core\Support\Exceptions\EncodingException
     *
     * @return void
     */
    #[TestWith([
        '+AREBYQENAQcBfg +i/ZncIJ+c5s +BBsEGQ +AMgA3wDB +MKswvzCrMMo-',
        'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ',
        Encoding::UTF_7
    ])]
    #[TestWith([
        '&AREBYQENAQcBfg- &i,ZncIJ+c5s- &BBsEGQ- &AMgA3wDB- &MKswvzCrMMo-',
        'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ',
        Encoding::UTF7_IMAP
    ])]
    public function testConvertEncoding (string $expected, string $actual, Encoding $to, ?Encoding $from = null):void {

        $this->assertSame(
            $expected,
            StrMB::convertEncoding($actual, $to, $from)
        );

    }

    /**
     * @since 1.0.0
     *
     * @param bool $expected
     * @param string $actual
     * @param null|\FireHub\Core\Support\Enums\String\Encoding $encoding
     *
     * @return void
     */
    #[TestWith([true, 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', null])]
    #[TestWith([false, 'đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ', Encoding::UTF_7])]
    public function testCheckEncoding (bool $expected, string $actual, ?Encoding $encoding):void {

        $this->assertSame($expected, StrMB::checkEncoding($actual, $encoding));

    }

}