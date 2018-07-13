<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 12.07.2018
 * Time: 19:12
 */

namespace WHMCS\Module\Addon\PushNotificationsForAdmin;

use \GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use \GuzzleHttp\Promise\PromiseInterface;

class FirebaseCloudMessagingController {
	/**
	 * @var Client
	 */
	private $httpClient;

	/**
	 * FirebaseCloudMessagingController constructor.
	 */
	function __construct() {
		$this->httpClient = new  Client( [ 'base_uri' => 'https://fcm.googleapis.com/' ] );
	}

	/**
	 * @return FirebaseCloudMessageModel
	 */
	function message():FirebaseCloudMessageModel {
		return new FirebaseCloudMessageModel( $this );
	}

	/**
	 * @param FirebaseCloudMessageModel $payload
	 * @param $serverKey
	 *
	 * @return PromiseInterface
	 */
	function sendMessage( FirebaseCloudMessageModel $payload, $serverKey ): PromiseInterface {
		return $this->httpClient->requestAsync( 'POST', 'fcm/send', [
			'body'    => $payload->serialize(),
			'headers' => [
				'Content-Type'  => 'application/json',
				'Authorization' => 'key=' . $serverKey
			]
		] );
	}

	/**
	 * @param string $recipient
	 * @param string $serverKey
	 *
	 * @return ResponseInterface
	 */
	function infoRecipient(string $recipient,string $serverKey ):ResponseInterface {
		return $this->httpClient->request( 'POST', 'https://iid.googleapis.com/iid/info/' . $recipient . '?details=true', [
			'headers' => [
				'Authorization' => 'key=' . $serverKey
			]
		] );

	}
}