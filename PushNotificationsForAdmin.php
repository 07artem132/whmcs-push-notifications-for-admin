<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 07.07.2018
 * Time: 6:47
 */

use WHMCS\Module\Addon\PushForNewTickets\PushToken;
use WHMCS\Module\Addon\PushNotificationsForAdmin\ConfigController;
use WHMCS\Module\Addon\PushNotificationsForAdmin\PushTokens;
use WHMCS\Module\Addon\PushNotificationsForAdmin\NotificationUserConfig;

use Illuminate\Database\Capsule\Manager as Capsule;

require ROOTDIR . '/modules/addons/' . ConfigController::MODULE_NAME . '/vendor/autoload.php';

function PushNotificationsForAdmin_config() {
	$config = [
		"name"        => "Push уведомления",
		"description" => "Данный модуль работает только для администраторов.",
		"version"     => "1",
		"author"      => "service-voice",
		"fields"      => [
			"note1"             => [
				"FriendlyName" => "Заметка:",
				"Description"  => "Для настройки модуля перейдите к административному выводу (это необходимо сделать каждому кто хочет получать уведомления).",
			],
			"ServerKey"         => [
				"FriendlyName" => "Ключ сервера",
				"Type"         => "text",
				"Size"         => "25",
				"Description"  => "Вы получите данное значение в Firebase Cloud Messaging",
			],
			"apiKey"            => [
				"FriendlyName" => "apiKey",
				"Type"         => "text",
				"Size"         => "25",
				"Description"  => "Вы получите данное значение в Firebase Cloud Messaging",
			],
			"authDomain"        => [
				"FriendlyName" => "authDomain",
				"Type"         => "text",
				"Size"         => "25",
				"Description"  => "Вы получите данное значение в Firebase Cloud Messaging",
			],
			"databaseURL"       => [
				"FriendlyName" => "databaseURL",
				"Type"         => "text",
				"Size"         => "25",
				"Description"  => "Вы получите данное значение в Firebase Cloud Messaging",
			],
			"projectId"         => [
				"FriendlyName" => "projectId",
				"Type"         => "text",
				"Size"         => "25",
				"Description"  => "Вы получите данное значение в Firebase Cloud Messaging",
			],
			"storageBucket"     => [
				"FriendlyName" => "storageBucket",
				"Type"         => "text",
				"Size"         => "25",
				"Description"  => "Вы получите данное значение в Firebase Cloud Messaging",
			],
			"messagingSenderId" => [
				"FriendlyName" => "messagingSenderId",
				"Type"         => "text",
				"Size"         => "25",
				"Description"  => "Вы получите данное значение в Firebase Cloud Messaging",
			],
		]
	];

	return $config;
}

function PushNotificationsForAdmin_output( $var ) {
	if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
		NotificationUserConfig::add( $_SESSION['adminid'], $_POST['notify'] );
	}

	if ( isset( $_GET['ajax'] ) ) {
		if ( isset( $_GET['supportDepartment'] ) ) {
			print ( json_encode( Capsule::table( 'tblticketdepartments' )->get() ) );
			die();
		}
		if ( isset( $_GET['UserConfig'] ) ) {
			print ( json_encode( NotificationUserConfig::get( $_SESSION['adminid'] ) ) );
			die();
		}

	}

	echo file_get_contents( ROOTDIR . '/modules/addons/PushNotificationsForAdmin/template/notifyConfig.html' );
}

function PushNotificationsForAdmin_clientarea( $vars ) {
	if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
		if ( isset( $_SESSION['adminid'] ) ) {
			PushTokens::add( $_SESSION['adminid'], $_POST['token'] );
		}
		die( json_encode( [ 'status' => 'success' ] ) );
	} else {
		http_response_code( 500 );
		die( json_encode( [ 'status' => 'error' ] ) );
	}
}