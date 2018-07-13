<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 02.07.2018
 * Time: 15:59
 */

namespace WHMCS\Module\Addon\PushNotificationsForAdmin;

use  WHMCS\Module\Addon\Setting;

/**
 * Class ConfigController
 * @package WHMCS\Module\Addon\PostponeDueDate
 */
class ConfigController implements \ArrayAccess, \Iterator, \Countable {
	public const MODULE_NAME = 'PushNotificationsForAdmin';
	private static $storage = null;
	private static $position = null;

	/**
	 * @return string[]
	 */
	public function load(): void {
		array_walk( Setting::Module( self::MODULE_NAME )->get()->toArray(), function ( $val, $key ) {
			self::$storage[ $val['setting'] ] = $val['value'];
		} );
	}

	/**
	 * @return array
	 */
	function toArray() {
		if ( self::$storage === null ) {
			self::load();
		}

		return (array) self::$storage;
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet( $offset, $value ) {
		if ( self::$storage === null ) {
			self::load();
		}

		if ( is_null( $offset ) ) {
			self::$storage[] = $value;
		} else {
			self::$storage[ $offset ] = $value;
		}
	}

	/**
	 * @param mixed $offset
	 *
	 * @return bool
	 */
	public function offsetExists( $offset ) {
		if ( self::$storage === null ) {
			self::load();
		}

		return isset( self::$storage[ $offset ] );
	}

	/**
	 * @param mixed $offset
	 */
	public function offsetUnset( $offset ) {
		if ( self::$storage === null ) {
			self::load();
		}

		unset( self::$storage[ $offset ] );
	}

	/**
	 * @param mixed $offset
	 *
	 * @return mixed|null
	 */
	public function offsetGet( $offset ) {
		if ( self::$storage === null ) {
			self::load();
		}

		return isset( self::$storage[ $offset ] ) ? self::$storage[ $offset ] : null;
	}

	public function rewind() {
		if ( self::$storage === null ) {
			self::load();
		}
		self::$position = reset( array_keys( self::$storage ) );
	}

	public function current() {
		if ( self::$storage === null ) {
			self::load();
		}

		return self::$storage[ self::$position ];
	}

	public function key() {
		if ( self::$storage === null ) {
			self::load();
		}

		return self::$position;
	}

	public function next() {
		if ( self::$storage === null ) {
			self::load();
		}

		self::$position = array_keys( self::$storage )[ ++ array_flip( array_keys( self::$storage ) )[ self::$position ] ];
	}

	public function valid() {
		if ( self::$storage === null ) {
			self::load();
		}

		return isset( self::$storage[ self::$position ] );
	}

	public function count() {
		if ( self::$storage === null ) {
			self::load();
		}

		return sizeof( self::$storage );
	}
}