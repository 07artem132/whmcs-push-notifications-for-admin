<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 07.07.2018
 * Time: 22:57
 */

use WHMCS\Module\Addon\PushNotificationsForAdmin\ConfigController;
use WHMCS\Module\Addon\PushNotificationsForAdmin\NotificationUserConfig;
use WHMCS\Module\Addon\PushNotificationsForAdmin\PushTokens;
use WHMCS\Module\Addon\PushNotificationsForAdmin\FirebaseCloudMessagingController;

require ROOTDIR . '/modules/addons/' . ConfigController::MODULE_NAME . '/vendor/autoload.php';

add_hook( 'TicketOpen', 1, function ( $vars ) {
	global $CONFIG, $customadminpath;

	$userConfig = NotificationUserConfig::get( $_SESSION['adminid'] );

	if ( ! array_key_exists( 'newTickets', $userConfig ) ) {
		return;
	}

	if ( array_key_exists( 'newTicketsDepartament', $userConfig ) ) {
		if ( ! array_key_exists( $vars['deptid'], array_flip( $userConfig['newTicketsDepartament'] ) ) ) {
			return;
		}
	}

	$FCM    = new FirebaseCloudMessagingController();
	$Config = new ConfigController();
	$FCM    = $FCM->message()
	              ->setMessage( $vars['message'] )
	              ->setTitle( 'Новый тикет' )
	              ->setIconUrl( $CONFIG['SystemURL'] . '/modules/addons/' . $Config::MODULE_NAME . '/img/Ticket.png' )
	              ->setUrl( $CONFIG['SystemURL'] . '/' . $customadminpath . '/supporttickets.php?action=view&id=' . $vars['ticketid'] )
	              ->setServerKey( $Config['ServerKey'] );

	$UserTokens = PushTokens::get( $_SESSION['adminid'] );

	if ( count( $UserTokens ) >= 1 && count( $UserTokens ) < 1000 ) {
		$promise = $FCM->setRecipients( $UserTokens )->send();
		// debugs only
		//	$promise->then( function ( $value ) {
		//		$result = json_decode( $value->getBody()->getContents() );
		//	} );
		$promise->wait();
	}
} );

add_hook( 'TicketUserReply', 1, function ( $vars ) {
	global $CONFIG, $customadminpath;

	$userConfig = NotificationUserConfig::get( $_SESSION['adminid'] );

	if ( ! array_key_exists( 'TicketsReply', $userConfig ) ) {
		return;
	}

	if ( array_key_exists( 'TicketsReplyDepartament', $userConfig ) ) {
		if ( ! array_key_exists( $vars['deptid'], array_flip( $userConfig['TicketsReplyDepartament'] ) ) ) {
			return;
		}
	}

	$FCM    = new FirebaseCloudMessagingController();
	$Config = new ConfigController();
	$FCM    = $FCM->message()
	              ->setMessage( $vars['message'] )
	              ->setTitle( 'Новый ответ клиента' )
	              ->setIconUrl( $CONFIG['SystemURL'] . '/modules/addons/' . $Config::MODULE_NAME . '/img/ticketToWork.png' )
	              ->setUrl( $CONFIG['SystemURL'] . '/' . $customadminpath . '/supporttickets.php?action=view&id=' . $vars['ticketid'] )
	              ->setServerKey( $Config['ServerKey'] );

	$UserTokens = PushTokens::get( $_SESSION['adminid'] );

	if ( count( $UserTokens ) >= 1 && count( $UserTokens ) < 1000 ) {
		$promise = $FCM->setRecipients( $UserTokens )->send();
		// debugs only
		//	$promise->then( function ( $value ) {
		//		$result = json_decode( $value->getBody()->getContents() );
		//	} );
		$promise->wait();
	}
} );

add_hook( 'AdminAreaFooterOutput', 1, function ( $vars ) {
	$config = new ConfigController();

	$return = '<script>';
	$return .= 'const PushNotificationsForAdminFCMConfig = ';
	$return .= '{';
	$return .= 'apiKey: "' . $config['apiKey'] . '",';
	$return .= 'authDomain: "' . $config['authDomain'] . '",';
	$return .= 'databaseURL: "' . $config['databaseURL'] . '",';
	$return .= 'projectId: "' . $config['projectId'] . '",';
	$return .= 'storageBucket: "' . $config['storageBucket'] . '",';
	$return .= 'messagingSenderId: "' . $config['messagingSenderId'] . '"';
	$return .= '};';

	$userConfig = NotificationUserConfig::get( $_SESSION['adminid'] );
	if ( array_key_exists( 'AudioUrl', $userConfig ) ) {
		$return .= 'const PushNotificationsForAdminUserConfig = ';
		$return .= '{';
		$return .= 'AudioUrl: "' . $userConfig['AudioUrl'] . '",';
		$return .= '};';
	}
	$return .= '</script>';

	$return .= '<script type="text/javascript" async  src="/modules/addons/' . ConfigController::MODULE_NAME . '/js/app.js"></script>';

	return $return;
} );

add_hook( 'AdminAreaPage', 1, function ( $vars ) {
	global $customadminpath;

	if ( $_SERVER['REQUEST_URI'] === '/' . $customadminpath . '/firebase-messaging-sw.js' ) {
		header( 'Content-Type: application/javascript' );
		echo file_get_contents( ROOTDIR . '/modules/addons/' . ConfigController::MODULE_NAME . '/js/firebase-messaging-sw.js' );
		die();
	}

} );

add_hook( 'OrderPaid', 1, function ( $vars ) {
	global $CONFIG, $customadminpath;

	$userConfig = NotificationUserConfig::get( $_SESSION['adminid'] );

	if ( ! array_key_exists( 'PaidOrder', $userConfig ) ) {
		return;
	}

	$FCM    = new FirebaseCloudMessagingController();
	$Config = new ConfigController();
	$FCM    = $FCM->message()
	              ->setMessage( ' ' )
	              ->setTitle( 'Заказ оплачен' )
	              ->setIconUrl( $CONFIG['SystemURL'] . '/modules/addons/' . $Config::MODULE_NAME . '/img/orderPaid.png' )
	              ->setUrl( $CONFIG['SystemURL'] . '/' . $customadminpath . '/orders.php?action=view&id=' . $vars['orderId'] )
	              ->setServerKey( $Config['ServerKey'] );

	$UserTokens = PushTokens::get( $_SESSION['adminid'] );

	if ( count( $UserTokens ) >= 1 && count( $UserTokens ) < 1000 ) {
		$promise = $FCM->setRecipients( $UserTokens )->send();
		// debugs only
		//	$promise->then( function ( $value ) {
		//		$result = json_decode( $value->getBody()->getContents() );
		//	} );
		$promise->wait();
	}
} );

add_hook( 'AfterShoppingCartCheckout', 1, function ( $vars ) {
	global $CONFIG, $customadminpath;

	$userConfig = NotificationUserConfig::get( $_SESSION['adminid'] );

	if ( ! array_key_exists( 'newOrder', $userConfig ) ) {
		return;
	}

	$FCM    = new FirebaseCloudMessagingController();
	$Config = new ConfigController();
	$FCM    = $FCM->message()
	              ->setMessage( 'Сумма заказа: ' . $vars['TotalDue'] )
	              ->setTitle( 'Новый заказ' )
	              ->setIconUrl( $CONFIG['SystemURL'] . '/modules/addons/' . $Config::MODULE_NAME . '/img/shopCart.jpg' )
	              ->setUrl( $CONFIG['SystemURL'] . '/' . $customadminpath . '/orders.php?action=view&id=' . $vars['OrderID'] )
	              ->setServerKey( $Config['ServerKey'] );

	$UserTokens = PushTokens::get( $_SESSION['adminid'] );

	if ( count( $UserTokens ) >= 1 && count( $UserTokens ) < 1000 ) {
		$promise = $FCM->setRecipients( $UserTokens )->send();
		// debugs only
		//	$promise->then( function ( $value ) {
		//		$result = json_decode( $value->getBody()->getContents() );
		//	} );
		$promise->wait();
	}
} );
