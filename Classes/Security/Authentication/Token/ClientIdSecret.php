<?php
namespace Kyoki\OAuth2\Security\Authentication\Token;

/*                                                                        *
 * This script belongs to the FLOW3 framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * An authentication token used for simple username and password authentication.
 */
class ClientIdSecret extends \TYPO3\FLOW3\Security\Authentication\Token\AbstractToken {

	/**
	 * The username/password credentials
	 * @var array
	 * @FLOW3\Transient
	 */
	protected $credentials = array('client_id' => '', 'client_secret' => '');

	/**
	 * Updates the username and password credentials from the POST vars, if the POST parameters
	 * are available. Sets the authentication status to REAUTHENTICATION_NEEDED, if credentials have been sent.
	 *
	 * Note: You need to send the username and password in these two POST parameters:
	 *       __authentication[TYPO3][FLOW3][Security][Authentication][Token][UsernamePassword][username]
	 *   and __authentication[TYPO3][FLOW3][Security][Authentication][Token][UsernamePassword][password]
	 *
	 * @param \TYPO3\FLOW3\Mvc\ActionRequest $actionRequest The current action request
	 * @return void
	 */
	public function updateCredentials(\TYPO3\FLOW3\Mvc\ActionRequest $actionRequest) {
		$httpRequest = $actionRequest->getHttpRequest();
		if ($httpRequest->getMethod() !== 'POST') {
			return;
		}

		$arguments = $actionRequest->getArguments();
		$username = \TYPO3\FLOW3\Reflection\ObjectAccess::getPropertyPath($arguments, 'client_id');
		$password = \TYPO3\FLOW3\Reflection\ObjectAccess::getPropertyPath($arguments, 'client_secret');

		if (!empty($username) && !empty($password)) {
			$this->credentials['client_id'] = $username;
			$this->credentials['client_secret'] = $password;

			$this->setAuthenticationStatus(self::AUTHENTICATION_NEEDED);
		}
	}

	/**
	 * Returns a string representation of the token for logging purposes.
	 *
	 * @return string The username credential
	 */
	public function  __toString() {
		return 'Client Id: "' . $this->credentials['client_id'] . '"';
	}

}
?>