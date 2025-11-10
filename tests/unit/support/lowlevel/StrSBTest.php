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

/**
 * ### Test single-byte string low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(StrSafe::class)]
#[CoversClass(StrSB::class)]
final class StrSBTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param bool $expected
     * @param string $actual
     * @param string $string
     *
     * @return void
     */
    #[TestWith([false, 'j', ''])]
    #[TestWith([true, 'j', 'ijk'])]
    public function testContains (bool $expected, string $actual, string $string):void {

        $this->assertSame($expected, StrSB::contains($actual, $string));

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
    #[TestWith([false, 'j', ''])]
    #[TestWith([true, 'i', 'ijk'])]
    public function testStartsWith (bool $expected, string $actual, string $string):void {

        $this->assertSame($expected, StrSB::startsWith($actual, $string));

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
    #[TestWith([false, 'j', ''])]
    #[TestWith([true, 'k', 'ijk'])]
    public function testEndsWith (bool $expected, string $actual, string $string):void {

        $this->assertSame($expected, StrSB::endsWith($actual, $string));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param null|string $characters
     *
     * @return void
     */
    #[TestWith(["O\\\\\\'Reilly", "O\'Reilly"])]
    #[TestWith(["\\O\\\\Reilly", 'O\Reilly', 'A..Z'])]
    #[TestWith(["OR\\e\\i\\l\\l\\y", 'OReilly', 'a..z', true])]
    #[TestWith(["O\'Reilly", "O'Reilly"])]
    #[TestWith(["O\\\\\\\"Reilly", 'O\"Reilly'])]
    #[TestWith(["O\\\\\\\"Reilly", 'O\"Reilly'])]
    #[TestWith(["O\\\\Reilly", 'O\\Reilly'])]
    #[TestWith(["O\\\\Reilly", 'O\Reilly'])]
    public function testAddStripSlashes (string $expected, string $actual, ?string $characters = null, bool $c_representation = false):void {

        $this->assertSame($expected, StrSB::addSlashes($actual, $characters));
        $this->assertSame($actual, StrSB::stripSlashes($expected, $c_representation));

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
    #[TestWith([' ', ['', ''], ' '])]
    #[TestWith(['The lazy fox jumped over the fence', ['The', 'lazy', 'fox', 'jumped', 'over', 'the', 'fence'], ' '])]
    #[TestWith(['The lazy fox - over the fence', ['The lazy fox ', ' over the fence'], '-'])]
    public function testImplodeExplode (string $expected, array $actual, string $separator):void {

        $this->assertSame($expected, StrSB::implode($actual, $separator));
        $this->assertSame($actual, StrSB::explode($expected, $separator));

    }

    /**
     * @since 1.0.0
     *
     * @param string $actual
     * @param string $separator
     *
     * @return void
     */
    #[TestWith(['The lazy fox jumped over the fence', ''])]
    public function testExplodeEmptyString (string $actual, string $separator):void {

        $this->expectException(EmptySeparatorException::class);

        StrSB::explode($actual, $separator);

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testImplodeCannotConvertString ():void {

        $this->expectException(DataException::class);

        StrSB::implode([fn() => '', ''], '');

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     *
     * @return void
     */
    #[TestWith([
        "PHP is a popular scripting language\. Fast, flexible, and pragmatic\.",
        'PHP is a popular scripting language. Fast, flexible, and pragmatic.'
    ])]
    public function testQuoteMeta (string $expected, string $actual):void {

        $this->assertSame($expected, StrSB::quoteMeta($actual));

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
    #[TestWith(['fox-fox', 'fox', 2, '-'])]
    public function testRepeat (string $expected, string $actual, int $times, string $separator):void {

        $this->assertSame($expected, StrSB::repeat($actual, $times, $separator));

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
        'Test paragraph. Other text',
        '<p>Test paragraph.</p><!-- Comment --> <a href="#fragment">Other text</a>'
    ])]
    #[TestWith([
        '<p>Test paragraph.</p> <a href="#fragment">Other text</a>',
        '<p>Test paragraph.</p><!-- Comment --> <a href="#fragment">Other text</a>',
        ['p', 'a']
    ])]
    public function testStripTags (string $expected, string $actual, null|string|array $allowed_tags = null):void {

        $this->assertSame($expected, StrSB::stripTags($actual, $allowed_tags));

    }

    /**
     * @since 1.0.0
     *
     * @param int<-1, 1> $expected
     * @param string $string_1
     * @param string $string_2
     * @param bool $case_sensitive
     *
     * @return void
     */
    #[TestWith([-1, 'a', 'z'])]
    #[TestWith([1, 'hello', 'Hello'])]
    #[TestWith([0, 'Hello', 'Hello'])]
    #[TestWith([1, 'a', 'A'])]
    #[TestWith([0, 'a', 'A', false])]
    public function testCompare (int $expected, string $string_1, string $string_2, bool $case_sensitive = true):void {

        $this->assertSame($expected, StrSB::compare($string_1, $string_2, $case_sensitive));

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
    #[TestWith(['Hello World', 'Hillo Warld', ['il' => 'el', 'ar' => 'or']])]
    public function testTranslate (string $expected, string $actual, array $replace_pairs):void {

        $this->assertSame($expected, StrSB::translate($actual, $replace_pairs));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param positive-int $length
     * @param string $separator
     *
     * @throws \FireHub\Core\Support\Exceptions\Str\ChunkLengthLessThanOneException
     *
     * @return void
     */
    #[TestWith([
        'The lazy f-ox jumped -over the f-ence-',
        'The lazy fox jumped over the fence',
        10,
        '-'
    ])]
    public function testChunkSplit (string $expected, string $actual, int $length, string $separator):void {

        $this->assertSame($expected, StrSB::chunkSplit($actual, $length, $separator));

    }

    /**
     * @since 1.0.0
     *
     * @param string $actual
     * @param positive-int $length
     * @param string $separator
     *
     * @return void
     */
    #[TestWith(['The lazy fox jumped over the fence', 0, '-'])]
    public function testChunkSplitLengthLessThanOne (string $actual, int $length, string $separator):void {

        $this->expectException(ChunkLengthLessThanOneException::class);

        StrSB::chunkSplit($actual, $length, $separator);

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param int $length
     * @param non-empty-string $pad
     * @param \FireHub\Core\Support\Enums\Side $side
     *
     * @throws \FireHub\Core\Support\Exceptions\Str\EmptyPadException
     *
     * @return void
     */
    #[TestWith([
        '----------------The lazy fox jumped over the fence',
        'The lazy fox jumped over the fence',
        50,
        '-',
        Side::LEFT
    ])]
    #[TestWith([
        'The lazy fox jumped over the fence----------------',
        'The lazy fox jumped over the fence',
        50,
        '-',
        Side::RIGHT
    ])]
    #[TestWith([
        '--------The lazy fox jumped over the fence--------',
        'The lazy fox jumped over the fence',
        50,
        '-',
        Side::BOTH
    ])]
    public function testPad (string $expected, string $actual, int $length, string $pad, Side $side):void {

        $this->assertSame($expected, StrSB::pad($actual, $length, $pad, $side));

    }

    /**
     * @since 1.0.0
     *
     * @param string $actual
     * @param int $length
     * @param non-empty-string $pad
     * @param \FireHub\Core\Support\Enums\Side $side
     *
     * @return void
     */
    #[TestWith([
        'The lazy fox jumped over the fence',
        50,
        '',
        Side::BOTH
    ])]
    public function testPadIsEmpty (string $actual, int $length, string $pad, Side $side):void {

        $this->expectException(EmptyPadException::class);

        StrSB::pad($actual, $length, $pad, $side);

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param string|list<string> $search
     * @param string|list<string> $replace
     * @param bool $case_sensitive
     * @param int $count_replaced
     *
     * @return void
     */
    #[TestWith([
        'The lazy mouse jumped over the fence',
        'The lazy fox jumped over the fence',
        'fox',
        'mouse',
        true,
        1
    ])]
    #[TestWith([
        'The lazy fox jumped over the fence',
        'The lazy fox jumped over the fence',
        'Fox',
        'mouse',
        true,
        0
    ])]
    #[TestWith([
        'The lazy fox, the lazy fox, the lazy fox',
        'The lazy mouse, the lazy mouse, the lazy mouse',
        'mouse',
        'fox',
        true,
        3
    ])]
    #[TestWith([
        'The lazy mouse jumped over the fence',
        'The lazy fox jumped over the fence',
        'Fox',
        'mouse',
        false,
        1
    ])]
    #[TestWith([
        'An lazy mouse jumped over the fence',
        'The lazy fox jumped over the fence',
        ['The', 'fox'],
        ['An',  'mouse'],
        true,
        2
    ])]
    public function testReplace (string $expected, string $actual, string|array $search, string|array $replace, bool $case_sensitive, int $count_replaced):void {

        $this->assertSame($expected, StrSB::replace($search, $replace, $actual, $case_sensitive, $count));
        $this->assertSame($count, $count_replaced);

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param string $replace
     * @param int $offset
     * @param null|int $length
     *
     * @return void
     */
    #[TestWith([
        'An lazy fox jumped over the fence',
        'The lazy fox jumped over the fence',
        'An',
        0,
        3
    ])]
    #[TestWith([
        'The lazy fox jumped over the bush',
        'The lazy fox jumped over the fence',
        'bush',
        -5,
        5
    ])]
    public function testReplacePart (string $expected, string $actual, string $replace, int $offset, ?int $length):void {

        $this->assertSame($expected, StrSB::replacePart($actual, $replace, $offset, $length));

    }

    /**
     * @since 1.0.0
     *
     * @param string $string
     *
     * @throws \FireHub\Core\Support\Exceptions\Str\ChunkLengthLessThanOneException
     *
     * @return void
     */
    #[DataProviderExternal(StringDataProvider::class, 'stringsSB')]
    #[Depends('testLength')]
    #[Depends('testSplit')]
    public function testShuffle (string $string):void {

        $shuffled = StrSB::shuffle($string);

        $this->assertSame(StrSB::length($string), StrSB::length($shuffled));

        $this->assertEqualsCanonicalizing(StrSB::split($string), StrSB::split($shuffled));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     *
     * @return void
     */
    #[TestWith(['ecnef eht revo depmuj xof yzal ehT', 'The lazy fox jumped over the fence'])]
    public function testReverse (string $expected, string $actual):void {

        $this->assertSame($expected, StrSB::reverse($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param int $width
     * @param string $break
     * @param bool $cut_long_words
     *
     * @return void
     */
    #[TestWith(['The lazy fox jumped<br />over the fence', 'The lazy fox jumped over the fence', 20, '<br />', true])]
    #[TestWith(['A very<br />long<br />wooooooo<br />ooooord', 'A very long woooooooooooord', 8, '<br />', true])]
    public function testWrap (string $expected, string $actual, int $width, string $break, bool $cut_long_words):void {

        $this->assertSame($expected, StrSB::wrap($actual, $width, $break, $cut_long_words));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param \FireHub\Core\Support\Enums\Side $side
     * @param string $characters
     *
     * @return void
     */
    #[TestWith([
        "These are a few words :) ...  \n\r",
        "\t\tThese are a few words :) ...  \n\r",
        Side::LEFT,
        " \n\r\t\v\x00"
    ])]
    #[TestWith([
        "\t\tThese are a few words :) ...",
        "\t\tThese are a few words :) ...",
        Side::RIGHT,
        " \n\r\t\v\x00"
    ])]
    #[TestWith([
        "These are a few words :) ...",
        "\t\tThese are a few words :) ...  \n\r",
        Side::BOTH,
        " \n\r\t\v\x00"
    ])]
    public function testTrim (string $expected, string $actual, Side $side, string $characters):void {

        $this->assertSame($expected, StrSB::trim($actual, $side, $characters));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     *
     * @return void
     */
    #[TestWith(['THE LAZY FOX JUMPED OVER THE FENCE', 'The lazy fox jumped over the fence'])]
    public function testToUpper (string $expected, string $actual):void {

        $this->assertSame($expected, StrSB::toUpper($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     *
     * @return void
     */
    #[TestWith(['the lazy fox jumped over the fence', 'The lazy fox jumped over the fence'])]
    public function testToLower (string $expected, string $actual):void {

        $this->assertSame($expected, StrSB::toLower($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     *
     * @return void
     */
    #[TestWith(['The Lazy Fox Jumped Over The Fence', 'The lazy fox jumped over the fence'])]
    public function testToTitle (string $expected, string $actual):void {

        $this->assertSame($expected, StrSB::toTitle($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     *
     * @return void
     */
    #[TestWith(['The lazy fox jumped over the fence', 'the lazy fox jumped over the fence'])]
    public function testCapitalize (string $expected, string $actual):void {

        $this->assertSame($expected, StrSB::capitalize($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     *
     * @return void
     */
    #[TestWith(['the lazy fox jumped over the fence', 'The lazy fox jumped over the fence'])]
    public function testDeCapitalize (string $expected, string $actual):void {

        $this->assertSame($expected, StrSB::deCapitalize($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param int $start
     * @param null|int $length
     *
     * @return void
     */
    #[TestWith(['azy fox jumped over the fence', 'The lazy fox jumped over the fence', 5])]
    #[TestWith(['ox j', 'The lazy fox jumped over the fence', 10, 4])]
    #[TestWith(['fen', 'The lazy fox jumped over the fence', -5, 3])]
    public function testPart (string $expected, string $actual, int $start, ?int $length = null):void {

        $this->assertSame($expected, StrSB::part($actual, $start, $length));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param string $find
     * @param bool $before_needle
     * @param bool $case_sensitive
     *
     * @return void
     */
    #[TestWith(['fox jumped over the fence', 'The lazy fox jumped over the fence', 'fox', false, true])]
    #[TestWith(['The lazy', 'The lazy fox jumped over the fence', ' fox', true, true])]
    #[TestWith([' fox jumped over the fence', 'The lazy fox jumped over the fence', ' Fox', false, false])]
    public function testFirstPart (string $expected, string $actual, string $find, bool $before_needle, bool $case_sensitive):void {

        $this->assertSame($expected, StrSB::firstPart($find, $actual, $before_needle, $case_sensitive));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param string $actual
     * @param string $find
     * @param bool $before_needle
     *
     * @return void
     */
    #[TestWith(['jumped over the fence', 'The lazy fox jumped over the fence', 'jumped', false])]
    public function testLastPart (string $expected, string $actual, string $find, bool $before_needle):void {

        $this->assertSame($expected, StrSB::lastPart($find, $actual, $before_needle));

    }

    /**
     * @since 1.0.0
     *
     * @param string|false $expected
     * @param string $actual
     * @param string $characters
     *
     * @return void
     */
    #[TestWith(['ox jumped over the fence', 'The lazy fox jumped over the fence', 'xov'])]
    #[TestWith([false, 'The lazy fox jumped over the fence', 'bqg'])]
    public function testPartFrom (string|false $expected, string $actual, string $characters):void {

        $this->assertSame($expected, StrSB::partFrom($characters, $actual));

    }

    /**
     * @since 1.0.0
     *
     * @param array<int, int> $expected
     * @param string $actual
     * @param \FireHub\Core\Support\Enums\String\Count\CharacterMode $mode
     *
     * @return void
     */
    #[TestWith([
        [
            32 => 6, 84 => 1, 97 => 1, 99 => 1, 100 => 1, 101 => 6, 102 => 2,
            104 => 2, 106 => 1, 108 => 1, 109 => 1, 110 => 1, 111 =>  2, 112 => 1,
            114 => 1, 116 => 1, 117 => 1, 118 => 1, 120 => 1, 121 => 1, 122 =>  1
        ],
        'The lazy fox jumped over the fence',
        CharacterMode::ARR_POSITIVE
    ])]
    public function testCountByChar (array $expected, string $actual, CharacterMode $mode):void {

        $this->assertSame($expected, StrSB::countByChar($actual, $mode));

    }

    /**
     * @since 1.0.0
     *
     * @param list<non-empty-string> $expected
     * @param non-empty-string $actual
     * @param positive-int $length
     *
     * @throws \FireHub\Core\Support\Exceptions\Str\ChunkLengthLessThanOneException
     *
     * @return void
     */
    #[TestWith([
        [
            0 => 'The l',
            1 => 'azy f',
            2 => 'ox ju',
            3 => 'mped ',
            4 => 'over ',
            5 => 'the f',
            6 => 'ence',
        ],
        'The lazy fox jumped over the fence',
        5
    ])]
    public function testSplit (array $expected, string $actual, int $length):void {

        $this->assertSame($expected, StrSB::split($actual, $length));

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $actual
     * @param positive-int $length
     *
     * @return void
     */
    #[TestWith(['The lazy fox jumped over the fence', 0])]
    public function testSplitLengthLessThanOne (string $actual, int $length):void {

        $this->expectException(ChunkLengthLessThanOneException::class);

        StrSB::split($actual, $length);

    }

    /**
     * @since 1.0.0
     *
     * @param int|array<int, string> $expected
     * @param non-empty-string $actual
     * @param null|string $characters
     * @param \FireHub\Core\Support\Enums\String\Count\WordFormat $format
     *
     * @throws \FireHub\Core\Support\Exceptions\Str\CountWordsException
     *
     * @return void
     */
    #[TestWith([
        7,
        'The lazy fox jumped3over the fence',
        null,
        WordFormat::WORDS
    ])]
    #[TestWith([
        6,
        'The lazy fox jumped3over the fence',
        'jumped3over',
        WordFormat::WORDS
    ])]
    #[TestWith([
        [
            0 => 'The',
            1 => 'lazy',
            2 => 'fox',
            3 => 'jumped',
            4 => 'over',
            5 => 'the',
            6 => 'fence'
        ],
        'The lazy fox jumped over the fence',
        null,
        WordFormat::ARR_WORDS
    ])]
    #[TestWith([
        [
            0 => 'The',
            4 => 'lazy',
            9 => 'fox',
            13 => 'jumped',
            20 => 'over',
            25 => 'the',
            29 => 'fence'
        ],
        'The lazy fox jumped over the fence',
        null,
        WordFormat::ASSOC_ARR_WORDS
    ])]
    public function testCountWords (int|array $expected, string $actual, ?string $characters, WordFormat $format):void {

        $this->assertSame($expected, StrSB::countWords($actual, $characters, $format));

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $actual
     * @param null|string $characters
     * @param \FireHub\Core\Support\Enums\String\Count\WordFormat $format
     *
     * @return void
     */
    #[TestWith(['', '', WordFormat::WORDS])]
    public function testCountWordsFailed (string $actual, ?string $characters, WordFormat $format):void {

        $this->expectException(CountWordsException::class);

        StrSB::countWords($actual, $characters, $format);

    }

    /**
     * @since 1.0.0
     *
     * @param non-negative-int $expected
     * @param string $actual
     * @param string $search
     * @param int $start
     * @param null|int $length
     *
     * @return void
     */
    #[TestWith([2, 'This is a test', 'is', 0 , null])]
    #[TestWith([1, 'This is a test', 'is', 3 , null])]
    #[TestWith([0, 'This is a test', 'is', 3 , 3])]
    public function testPartCount (int $expected, string $actual, string $search, int $start, ?int $length):void {

        $this->assertSame($expected, StrSB::partCount($actual, $search, $start, $length));

    }

    /**
     * @since 1.0.0
     *
     * @param non-negative-int $expected
     * @param string $actual
     * @param string $characters
     * @param int $offset
     * @param null|int $length
     *
     * @return void
     */
    #[TestWith([0, 'The lazy fox jumped over the fence', 'lazy', 0 , null])]
    #[TestWith([4, 'The lazy fox jumped over the fence', 'lazy', 4 , null])]
    #[TestWith([3, 'The lazy fox jumped over the fence', 'lazy', 4, 3])]
    public function testSegmentMatching (int $expected, string $actual, string $characters, int $offset, ?int $length):void {

        $this->assertSame($expected, StrSB::segmentMatching($actual, $characters, $offset, $length));

    }

    /**
     * @since 1.0.0
     *
     * @param non-negative-int $expected
     * @param string $actual
     * @param string $characters
     * @param int $offset
     * @param null|int $length
     *
     * @return void
     */
    #[TestWith([4, 'The lazy fox jumped over the fence', 'lazy', 0 , null])]
    #[TestWith([0, 'The lazy fox jumped over the fence', 'lazy', 4 , null])]
    #[TestWith([2, 'The lazy fox jumped over the fence', 'lazy', 2, 4])]
    public function testSegmentNotMatching (int $expected, string $actual, string $characters, int $offset, ?int
                                                $length):void {

        $this->assertSame($expected, StrSB::segmentNotMatching($actual, $characters, $offset, $length));

    }

    /**
     * @since 1.0.0
     *
     * @param non-negative-int $expected
     * @param string $actual
     *
     * @return void
     */
    #[TestWith([34, 'The lazy fox jumped over the fence'])]
    #[TestWith([0, ''])]
    public function testLength (int $expected, string $actual):void {

        $this->assertSame($expected, StrSB::length($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param int<-1, 1> $expected
     * @param string $string_1
     * @param string $string_2
     * @param int $offset
     * @param null|int $length
     * @param bool $case_sensitive
     *
     * @throws \FireHub\Core\Support\Exceptions\Str\ComparePartException
     *
     * @return void
     */
    #[TestWith([0, 'abcde', 'BC', 1, 2, false])]
    #[TestWith([1, 'abcde', 'BC', 1, 3, true])]
    #[TestWith([-1, 'abcde', 'cd', 1, 2, true])]
    public function testComparePart (int $expected, string $string_1, string $string_2, int $offset, ?int $length, bool $case_sensitive):void {

        $this->assertSame($expected, StrSB::comparePart($string_1, $string_2, $offset, $length, $case_sensitive));

    }

    /**
     * @since 1.0.0
     *
     * @param string $string_1
     * @param string $string_2
     * @param int $offset
     * @param null|int $length
     * @param bool $case_sensitive
     *
     * @return void
     */
    #[TestWith(['abcde', 'BC', 10, null, true])]
    public function testComparePartOffsetHigherThenString (string $string_1, string $string_2, int $offset, ?int $length, bool $case_sensitive):void {

        $this->expectException(ComparePartException::class);

        StrSB::comparePart($string_1, $string_2, $offset, $length, $case_sensitive);

    }

    /**
     * @since 1.0.0
     *
     * @param int<-1, 1> $expected
     * @param string $string_1
     * @param string $string_2
     * @param int $length
     *
     * @return void
     */
    #[TestWith([1, 'Hello John', 'Hello Doe', 50])]
    #[TestWith([0, 'Hello John', 'Hello Doe', 5])]
    public function testCompareFirstN (int $expected, string $string_1, string $string_2, int $length):void {

        $this->assertSame($expected, StrSB::compareFirstN($string_1, $string_2, $length));

    }

    /**
     * @since 1.0.0
     *
     * @param non-negative-int|false $expected
     * @param string $actual
     * @param string $search
     * @param bool $case_sensitive
     * @param int $offset
     *
     * @return void
     */
    #[TestWith([false, 'The lazy fox jumped over the fence', 'Fox', true, 0])]
    #[TestWith([9, 'The lazy fox jumped over the fence', 'Fox', false, 0])]
    #[TestWith([9, 'The lazy fox jumped over the fence', 'Fox', false, 9])]
    #[TestWith([0, 'The lazy fox jumped over the fence', 'T', true, 0])]
    #[TestWith([false, 'The lazy fox jumped over the fence', 'Fox', false, 10])]
    public function testFirstPosition (int|false $expected, string $actual, string $search, bool $case_sensitive, int $offset):void {

        $this->assertSame($expected, StrSB::firstPosition($search, $actual, $case_sensitive, $offset));

    }

    /**
     * @since 1.0.0
     *
     * @param non-negative-int|false $expected
     * @param string $actual
     * @param string $search
     * @param bool $case_sensitive
     * @param int $offset
     *
     * @return void
     */
    #[TestWith([false, 'The lazy fox jumped over the fence', 'Fox', true, 0])]
    #[TestWith([9, 'The lazy fox jumped over the fence', 'Fox', false, 0])]
    #[TestWith([9, 'The lazy fox jumped over the fence', 'Fox', false, 9])]
    #[TestWith([0, 'The lazy fox jumped over the fence', 'T', true, 0])]
    #[TestWith([false, 'The lazy fox jumped over the fence', 'Fox', false, 10])]
    public function testLastPosition (int|false $expected, string $actual, string $search, bool $case_sensitive, int $offset):void {

        $this->assertSame($expected, StrSB::lastPosition($search, $actual, $case_sensitive, $offset));

    }

}