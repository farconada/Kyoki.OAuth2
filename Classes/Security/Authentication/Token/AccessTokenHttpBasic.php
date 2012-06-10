<?php
namespace Kyoki\OAuth2\Security\Authentication\Token;
use TYPO3\FLOW3\Annotations as FLOW3;

/*                                                                        *
 * This script belongs to the FLOW3 framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * An authentication token used for simple username and password authentication via HTTP Basic Auth.
 *
 */
class AccessTokenHttpBasic extends ClientIdSecret {

    /**
     * The username/password credentials
     * @var array
     * @FLOW3\Transient
     */
    protected $credentials = array('access_token' => '');

    /**
     * @var $oauthToken \Kyoki\OAuth2\Domain\Model\OAuthToken
     */
    protected $oauthToken = NULL;

	/**
	 * Updates the username and password credentials from the HTTP authorization header.
	 * Sets the authentication status to AUTHENTICATION_NEEDED, if the header has been
	 * sent, to NO_CREDENTIALS_GIVEN if no authorization header was there.
	 *
	 * @param \TYPO3\FLOW3\Mvc\ActionRequest $actionRequest The current action request instance
	 * @return void
	 */
	public function updateCredentials(\TYPO3\FLOW3\Mvc\ActionRequest $actionRequest) {
        $headers = array();
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        }
        if (!isset($headers['Authorization'])) {
            $headers['Authorization'] = NULL;
        }
		$authorizationHeader = $headers['Authorization'];
		if (substr($authorizationHeader, 0, 5) === 'OAuth') {
			$this->credentials['access_token'] = trim(substr($authorizationHeader, 6));
			$this->setAuthenticationStatus(self::AUTHENTICATION_NEEDED);
		} else {
			$this->credentials = array('access_token' => NULL);
			$this->authenticationStatus = self::NO_CREDENTIALS_GIVEN;
		}
	}

    /**
     * Returns a string representation of the token for logging purposes.
     *
     * @return string The access token credential
     */
    public function  __toString() {
        return 'Access Token: "' . $this->credentials['access_token'] . '"';
    }

    /**
     * @param \Kyoki\OAuth2\Domain\Model\OAuthToken $oauthToken
     */
    public function setOauthToken($oauthToken)
    {
        $this->oauthToken = $oauthToken;
    }

    /**
     * @return \Kyoki\OAuth2\Domain\Model\OAuthToken
     */
    public function getOauthToken()
    {
        return $this->oauthToken;
    }

}
?>
