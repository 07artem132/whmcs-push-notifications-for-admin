<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 12.07.2018
 * Time: 20:27
 */

namespace WHMCS\Module\Addon\PushNotificationsForAdmin;

use \GuzzleHttp\Promise\PromiseInterface;

class FirebaseCloudMessageModel implements \Serializable {
	private
		/**
		 * @var string
		 */
		$title = '',
		/**
		 * @var string
		 */
		$message = '',
		/**
		 * @var string
		 */
		$iconUrl = '',
		/**
		 * @var string
		 */
		$url = '',
		/**
		 * @var ?array
		 */
		$recipient = null,
		/**
		 * @var ?array
		 */
		$recipients = null,
		/**
		 * @var FirebaseCloudMessagingController
		 */
		$parent,
		/**
		 * @var string
		 */
		$serverKey = '';

	/**
	 * FirebaseCloudMessageModel constructor.
	 *
	 * @param FirebaseCloudMessagingController $parent
	 */
	function __construct( FirebaseCloudMessagingController $parent ) {
		$this->parent = $parent;
	}

	/**
	 * @return PromiseInterface
	 */
	public function send(): PromiseInterface {
		return $this->parent->sendMessage( $this, $this->serverKey );
	}

	/**
	 * @return string
	 */
	public function serialize(): string {
		$return = [
			'notification' => [
				'title' => $this->title,
				'body'  => $this->message,
				'icon'  => $this->iconUrl,
			],
			'data'         => [
				'url' => $this->url
			]
		];
		if ( $this->recipients != null ) {
			$return['registration_ids'] = $this->recipients;
		} else {
			$return['to'] = $this->recipient;
		}

		return json_encode( $return );
	}

	/**
	 * @param string $serialized
	 */
	public function unserialize( $serialized ): void {
		$data = json_decode( $serialized );

		if ( array_key_exists( 'notification', $data ) ) {
			if ( array_key_exists( 'body', $data['notification'] ) ) {
				$this->message = $data['notification']['body'];
			}
			if ( array_key_exists( 'icon', $data['notification'] ) ) {
				$this->iconUrl = $data['notification']['icon'];
			}
			if ( array_key_exists( 'title', $data['notification'] ) ) {
				$this->title = $data['notification']['title'];
			}
		}

		if ( array_key_exists( 'data', $data ) ) {
			if ( array_key_exists( 'url', $data['data'] ) ) {
				$this->url = $data['data']['url'];
			}
		}

		if ( array_key_exists( 'registration_ids', $data ) ) {
			$this->recipients = $data['registration_ids'];
		}

		if ( array_key_exists( 'to', $data ) ) {
			$this->recipient = $data['to'];
		}

	}

	/**
	 * @param string $serverKey
	 *
	 * @return FirebaseCloudMessageModel
	 */
	function setServerKey( string $serverKey ): FirebaseCloudMessageModel {
		$this->serverKey = $serverKey;

		return $this;
	}

	/**
	 * @return string
	 */
	function getServerKey(): string {
		return $this->serverKey;
	}

	/**
	 * @param string $title
	 *
	 * @return FirebaseCloudMessageModel
	 */
	function setTitle( string $title ): FirebaseCloudMessageModel {
		$this->title = $title;

		return $this;
	}

	/**
	 * @param string $message
	 *
	 * @return FirebaseCloudMessageModel
	 */
	function setMessage( string $message ): FirebaseCloudMessageModel {
		$this->message = $message;

		return $this;
	}

	/**
	 * @param string $iconUrl
	 *
	 * @return FirebaseCloudMessageModel
	 */
	function setIconUrl( string $iconUrl ): FirebaseCloudMessageModel {
		$this->iconUrl = $iconUrl;

		return $this;
	}

	/**
	 * @param string $recipient
	 *
	 * @return FirebaseCloudMessageModel
	 */
	function setRecipient( string $recipient ): FirebaseCloudMessageModel {
		$this->recipient = $recipient;

		return $this;
	}

	/**
	 * @param array $recipients
	 *
	 * @return FirebaseCloudMessageModel
	 */
	function setRecipients( array $recipients ): FirebaseCloudMessageModel {
		$this->recipients = $recipients;

		return $this;
	}

	/**
	 * @param string $url
	 *
	 * @return FirebaseCloudMessageModel
	 */
	function setUrl( string $url ): FirebaseCloudMessageModel {
		$this->url = $url;

		return $this;
	}

	/**
	 * @return string
	 */
	function getTitle(): string {
		return $this->title;
	}

	/**
	 * @return string
	 */
	function getMessage(): string {
		return $this->message;
	}

	/**
	 * @return string
	 */
	function getIconUrl(): string {
		return $this->iconUrl;
	}

	/**
	 * @return string
	 */
	function getRecipient(): string {
		return $this->recipient;
	}

	/**
	 * @return array
	 */
	function getRecipients(): array {
		return $this->recipients;
	}

	/**
	 * @return string
	 */
	function getUrl(): string {
		return $this->url;
	}

}