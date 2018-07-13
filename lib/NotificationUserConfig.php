<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 13.07.2018
 * Time: 15:22
 */

namespace WHMCS\Module\Addon\PushNotificationsForAdmin;

use WHMCS\Config\Setting;

/**
 * Class NotificationUserConfig
 * @package WHMCS\Module\Addon\PushNotificationsForAdmin
 */
class NotificationUserConfig {
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
	public static function toArray(): array {
		if ( self::$storage === null ) {
			self::load();
		}

		return (array) self::$storage;
	}

	/**
	 * @param int $user_id
	 * @param array|null $config
	 */
	public static function add( int $user_id, ?array $config ): void {
		if ( self::$storage === null ) {
			self::load();
		}

		self::$storage[ $user_id ] = $config;

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
		self::$storage = [];
		self::save();
	}


}