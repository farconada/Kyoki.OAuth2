<?php
namespace Kyoki\OAuth2\Security\Authentication\Token;

/*                                                                        *
* This script belongs to the Kyoki.OAuth2 package.                        *
* @author Fernando Arconada <fernando.arconada@gmail.com>                *
*
* It is free software; you can redistribute it and/or modify it under    *
* the terms of the GNU Lesser General Public License, either version 3   *
* of the License, or (at your option) any later version.                 *
*                                                                        *
*                                                                        */

/**
 * An authentication token used for simple username and password authentication via HTTP Basic Auth.
 *
 */
class ClientIdSecretHttpBasic extends ClientIdSecret {

	/**
	 * Updates the username and password credentials from the HTTP authorization header.
	 * Sets the authentication status to AUTHENTICATION_NEEDED, if the header has been
	 * sent, to NO_CREDENTIALS_GIVEN if no authorization header was there.
	 *
	 * @param \TYPO3\FLOW3\Mvc\ActionRequest $actionRequest The current action request instance
	 * @return void
	 */
	public function updateCredentials(\TYPO3\FLOW3\Mvc\ActionRequest $actionRequest) {
		$authorizationHeader = $actionRequest->getHttpRequest()->getHeaders()->get('Authorization');
		if (substr($authorizationHeader, 0, 5) === 'Basic') {
			$credentials = base64_decode(substr($authorizationHeader, 6));
			$this->credentials['client_id'] = substr($credentials, 0, strpos($credentials, ':'));
			$this->credentials['client_secret'] = substr($credentials, strpos($credentials, ':') + 1);
			$this->setAuthenticationStatus(self::AUTHENTICATION_NEEDED);
		} else {
			$this->credentials = array('client_id' => NULL, 'client_secret' => NULL);
			$this->authenticationStatus = self::NO_CREDENTIALS_GIVEN;
		}
	}
}
?>
