<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 08.07.2018
 * Time: 16:31
 */

namespace WHMCS\Module\Addon\PushNotificationsForAdmin;

use WHMCS\Config\Setting;

class PushTokens {
	/**
	 * @var array|string
	 */
	private static $storage;

	private static function load(): void {
		self::$storage = Setting::getValue( ConfigController::MODULE_NAME . __CLASS__ );

		if ( self::$storage === null ) {
			self::$storage = [];
		} else {
			self::$storage = json_decode( self::$storage, true );
		}
	}

	private static function save(): void {
		Setting::setValue( ConfigController::MODULE_NAME . __CLASS__, json_encode( self::$storage ) );
	}

	/**
	 * @return array
	 */
	public static function toArray() {
		if ( self::$storage === null ) {
			self::load();
		}

		return (array) self::$storage;
	}

	/**
	 * @param int $user_id
	 * @param string $token
	 */
	public static function add( int $user_id, string $token ): void {
		if ( self::$storage === null ) {
			self::load();
		}

		if ( ! array_key_exists( $user_id, self::$storage ) || array_search( $token, self::$storage[ $user_id ] ) === false ) {
			self::$storage[ $user_id ][] = $token;
		}

		self::save();
	}

	/**
	 * @param int $user_id
	 *
	 * @return array|null
	 */
	public static function get( int $user_id ): ?array {
		if ( self::$storage === null ) {
			self::load();
		}

		return self::$storage[ $user_id ];
	}

	/**
	 * @param int $user_id
	 */
	public static function deleteUser( int $user_id ): void {
		if ( self::$storage === null ) {
			self::load();
		}

		unset( self::$storage[ $user_id ] );

		self::save();
	}


	public static function clear(): void {
		if ( self::$storage === null ) {
			self::load();
		}

		self::$storage = [];
		self::save();
	}


}