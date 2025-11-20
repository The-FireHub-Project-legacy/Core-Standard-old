<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 8.5
 * @package Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\Debug;

use FireHub\Core\Support\LowLevel\ {
    DataIs, JSON, Obj, Resources
};
use Stringable;

/**
 * ### Convert value to string
 * @since 1.0.0
 */
final class ValueStringifier {

    /**
     * ### Convert value to string
     *
     * <code>
     * use \FireHub\Core\Support\Debug\ValueStringifier;
     *
     * $str = new ValueStringifier()->stringify(true);
     *
     * // 'true'
     *
     * $str = new ValueStringifier()->stringify(false);
     *
     * // 'false'
     *
     * $str = new ValueStringifier()->stringify(null);
     *
     * // 'null'
     *
     * $str = new ValueStringifier()->stringify(10);
     *
     * // '10'
     *
     * $str = new ValueStringifier()->stringify('FireHub');
     *
     * // 'FireHub'
     *
     * $str = new ValueStringifier()->stringify([1,2,3]);
     *
     * // '[1,2,3]'
     *
     * $str = new ValueStringifier()->stringify(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * // '{"firstname":"John","lastname":"Doe","age":25,"10":2}'
     *
     * $str = new ValueStringifier()->stringify(
     *  new class implements Stringable {
     *      public function __toString():string {
     *          return 'hi!';
     *  }
     * });
     *
     * // 'hi!'
     *
     * $str = new ValueStringifier()->stringify(new stdClass);
     *
     * // 'object(stdClass)[74]'
     *
     * $str = new ValueStringifier()->stringify(new stdClass, false);
     *
     * // 'stdClass'
     *
     * $str = new ValueStringifier()->stringify(fopen('php://stdout', 'wb'));
     *
     * // 'resource(7, stream)'
     *
     * $str = new ValueStringifier()->stringify(fopen('php://stdout', 'wb'), false);
     *
     * // 'resource'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Debug\ValueStringifier::stringify() To try to convert scalar to value.
     * @uses \FireHub\Core\Support\Debug\ValueStringifier::arr() To try to convert arr to value.
     * @uses \FireHub\Core\Support\Debug\ValueStringifier::stringable() To try to convert stringable to value.
     * @uses \FireHub\Core\Support\Debug\ValueStringifier::object() To try to convert an object to value.
     * @uses \FireHub\Core\Support\Debug\ValueStringifier::resource() To try to convert resource to value.
     *
     * @param mixed $value <p>
     * The variable being converted.
     * </p>
     * @param bool $detailed [optional] <p>
     * If true, objects and resources will have their ID and type if one exists.
     * </p>
     *
     * @return string Value converted to string.
     */
    public function stringify (mixed $value, bool $detailed = true, string $default = ''):string {

        return $value
            |> (fn($v) => $this->scalar($v))
            |> (fn($v) => $v ?? $this->arr($v))
            |> (fn($v) => $v ?? $this->stringable($v))
            |> (fn($v) => $v ?? $this->object($v, $detailed))
            |> (fn($v) => $v ?? $this->resource($v, $detailed))
            ?? $default;

    }

    /**
     * ### Convert scalar to string
     *
     * <code>
     * use \FireHub\Core\Support\Debug\ValueStringifier;
     *
     * $str = new ValueStringifier()->scalar(true);
     *
     * // 'true'
     *
     * $str = new ValueStringifier()->scalar(false);
     *
     * // 'false'
     *
     * $str = new ValueStringifier()->scalar(null);
     *
     * // 'null'
     *
     * $str = new ValueStringifier()->scalar(10);
     *
     * // '10'
     *
     * $str = new ValueStringifier()->scalar('FireHub');
     *
     * // 'FireHub'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::string() To convert $value to string.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::numeric() To convert $value to string.
     *
     * @param mixed $value <p>
     * The variable being converted.
     * </p>
     *
     * @return null|string Scalar converted to string or null if the value is not a scalar.
     */
    public function scalar (mixed $value):?string {

        return match (true) {
            $value === true  => 'true',
            $value === false => 'false',
            $value === null  => 'null',
            DataIs::string($value)  => $value,
            DataIs::numeric($value) => (string)$value,
            default => null
        };

    }

    /**
     * ### Convert array to string
     *
     * <code>
     * use \FireHub\Core\Support\Debug\ValueStringifier;
     *
     * $str = new ValueStringifier()->arr([1,2,3]);
     *
     * // '[1,2,3]'
     *
     * $str = new ValueStringifier()->arr(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * // '{"firstname":"John","lastname":"Doe","age":25,"10":2}'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::array() To convert $value to string.
     * @uses \FireHub\Core\Support\LowLevel\JSON::encode() To convert an array to JSON.
     *
     * @param mixed $value <p>
     * The variable being converted.
     * </p>
     *
     * @throws \FireHub\Core\Support\Exceptions\JSON\EncodingException If JSON encoding throws an error.
     *
     * @return null|string Array converted to string or null if the value is not an array.
     */
    public function arr (mixed $value):?string {

        if (!DataIs::array($value)) return null;

        return JSON::encode($value) ?: '[]';

    }

    /**
     * ### Convert stringable to string
     *
     * <code>
     * use \FireHub\Core\Support\Debug\ValueStringifier;
     *
     * $str = new ValueStringifier()->stringable(
     *  new class implements Stringable {
     *      public function __toString():string {
     *          return 'hi!';
     *  }
     * });
     *
     * // 'hi!'
     * </code>
     *
     * @since 1.0.0
     *
     * @param mixed $value <p>
     * The variable being converted.
     * </p>
     *
     * @return null|string Stringable converted to string or null if the value is not a stringable.
     */
    public function stringable (mixed $value):?string {

        return $value instanceof Stringable ? (string)$value : null;

    }

    /**
     * ### Convert object to string
     *
     * <code>
     * use \FireHub\Core\Support\Debug\ValueStringifier;
     *
     * $str = new ValueStringifier()->object(new stdClass);
     *
     * // 'object(stdClass)[74]'
     * </code>
     * Without details:
     * <code>
     * use \FireHub\Core\Support\Debug\ValueStringifier;
     *
     * $str = new ValueStringifier()->object(new stdClass, false);
     *
     * // 'stdClass'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::object() To convert $value to an object.
     * @uses \FireHub\Core\Support\LowLevel\Obj::id() To get ID from an object.
     *
     * @param mixed $value <p>
     * The variable being converted.
     * </p>
     * @param bool $detailed [optional] <p>
     * If true, objects will have their ID and type if one exists.
     * </p>
     *
     * @return null|string Object converted to string or null if the value is not an object.
     */
    public function object (mixed $value, bool $detailed = true):?string {

        if (!DataIs::object($value)) return null;

        return $detailed
            ? 'object('.$value::class.')['.Obj::id($value).']'
            : $value::class;

    }

    /**
     * ### Convert resource to string
     *
     * <code>
     * use \FireHub\Core\Support\Debug\ValueStringifier;
     *
     * $str = new ValueStringifier()->resource(fopen('php://stdout', 'wb'));
     *
     * // 'resource(7, stream)'
     * </code>
     * Without details:
     * <code>
     * use \FireHub\Core\Support\Debug\ValueStringifier;
     *
     * $str = new ValueStringifier()->resource(fopen('php://stdout', 'wb'), false);
     *
     * // 'resource'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::resource() To convert $value to resource.
     * @uses \FireHub\Core\Support\LowLevel\Resources::id() To get ID from resource.
     * @uses \FireHub\Core\Support\LowLevel\Resources::type() To get a type from resource.
     *
     * @param mixed $value <p>
     * The variable being converted.
     * </p>
     * @param bool $detailed [optional] <p>
     * If true, resources will have their ID and type if one exists.
     * </p>
     *
     * @return null|string Resource converted to string or null if the value is not a resource.
     */
    public function resource (mixed $value, bool $detailed = true):?string {

        if (!DataIs::resource($value)) return null;

        return $detailed
            ? 'resource('.Resources::id($value).', '.Resources::type($value)?->value.')'
            : 'resource';

    }

}