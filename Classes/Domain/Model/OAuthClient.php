<?php
namespace Kyoki\OAuth2\Domain\Model;
/*                                                                        *
 * This script belongs to the Kyoki.OAuth2 package.                        *
 * @author Fernando Arconada <fernando.arconada@gmail.com>                *
 *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 *                                                                        */
use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * An OAuth consumer
 *
 * @FLOW3\Entity
 */
class OAuthClient {
	/**
	 * @ORM\Id
	 * @FLOW3\Identity
	 * @var string
	 */
	protected $clientId;

	/**
	 * @var string
	 */
	protected $secret;

	/**
	 * @var string
	 */
	protected $redirectUri;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * You are authenticated as this account user when you request a new token
	 *
	 * @var \TYPO3\FLOW3\Security\Account
	 * @ORM\ManyToOne
	 */
	protected $account;


	// TODO party as constructor parameter???
	public function __construct($account,$description, $redirectUri) {
		$clientId = sha1(bin2hex(\TYPO3\FLOW3\Utility\Algorithms::generateRandomBytes(96)));
		$secret = sha1(bin2hex(\TYPO3\FLOW3\Utility\Algorithms::generateRandomBytes(96)));
		$this->setSecret($secret);
		$this->clientId = $clientId;
		$this->setDescription($description);
		$this->setRedirectUri($redirectUri);
		$this->account = $account;
	}


	/**
	 * @return string
	 */
	public function getClientId() {
		return $this->clientId;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	public function setAccount($account) {
		$this->account = $account;
	}

	public function getAccount() {
		return $this->account;
	}

	/**
	 * @param string $redirectUri
	 */
	public function setRedirectUri($redirectUri) {
		$this->redirectUri = $redirectUri;
	}

	/**
	 * @return string
	 */
	public function getRedirectUri() {
		return $this->redirectUri;
	}

	/**
	 * @param string $secret
	 */
	public function setSecret($secret) {
		$this->secret = $secret;
	}

	/**
	 * @return string
	 */
	public function getSecret() {
		return $this->secret;
	}

	/**
	 * @param string $clientId
	 */
	public function setClientId($clientId) {
		$this->clientId = $clientId;
	}


}
