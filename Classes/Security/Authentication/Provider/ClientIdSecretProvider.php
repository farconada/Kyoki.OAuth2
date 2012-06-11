<?php
namespace Kyoki\OAuth2\Security\Authentication\Provider;

/*                                                                        *
 * This script belongs to the Kyoki.OAuth2 package.                        *
 * @author Fernando Arconada <fernando.arconada@gmail.com>                *
 *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * An authentication provider that authenticates
 * TYPO3\FLOW3\Security\Authentication\Token\UsernamePassword tokens.
 * The accounts are stored in the Content Repository.
 */
class ClientIdSecretProvider extends \TYPO3\FLOW3\Security\Authentication\Provider\AbstractProvider {

	/**
	 * @var \Kyoki\OAuth2\Domain\Repository\OAuthClientRepository
	 * @FLOW3\Inject
	 */
	protected $oauthClientRepository;


	/**
	 * Returns the class names of the tokens this provider can authenticate.
	 *
	 * @return array
	 */
	public function getTokenClassNames() {
		return array('Kyoki\OAuth2\Security\Authentication\Token\ClientIdSecret', 'Kyoki\OAuth2\Security\Authentication\Token\ClientIdSecretHttpBasic');
	}

	/**
	 * Sets isAuthenticated to TRUE for all tokens.
	 *
	 * @param \TYPO3\FLOW3\Security\Authentication\TokenInterface $authenticationToken The token to be authenticated
	 * @return void
	 * @throws \TYPO3\FLOW3\Security\Exception\UnsupportedAuthenticationTokenException
	 * @FLOW3\Session(autoStart=true)
	 */
	public function authenticate(\TYPO3\FLOW3\Security\Authentication\TokenInterface $authenticationToken) {
		if (!($authenticationToken instanceof \Kyoki\OAuth2\Security\Authentication\Token\ClientIdSecret)) {
			throw new \TYPO3\FLOW3\Security\Exception\UnsupportedAuthenticationTokenException('This provider cannot authenticate the given token.', 1217339840);
		}

		$account = NULL;
		$credentials = $authenticationToken->getCredentials();

		if (is_array($credentials) && isset($credentials['client_id'])) {
			/**
			 * @var $oauthClient \Kyoki\OAuth2\Domain\Model\OAuthClient;
			 */
			$oauthClient = $this->oauthClientRepository->findByIdentifier($credentials['client_id']);
		}

		if (is_object($oauthClient)) {
			if ($oauthClient->getSecret() == $credentials['client_secret']) {
				$authenticationToken->setAuthenticationStatus(\TYPO3\FLOW3\Security\Authentication\TokenInterface::AUTHENTICATION_SUCCESSFUL);
				$authenticationToken->setAccount($oauthClient->getParty()->getAccounts()->first());
			} else {
				$authenticationToken->setAuthenticationStatus(\TYPO3\FLOW3\Security\Authentication\TokenInterface::WRONG_CREDENTIALS);
			}
		} elseif ($authenticationToken->getAuthenticationStatus() !== \TYPO3\FLOW3\Security\Authentication\TokenInterface::AUTHENTICATION_SUCCESSFUL) {
			$authenticationToken->setAuthenticationStatus(\TYPO3\FLOW3\Security\Authentication\TokenInterface::NO_CREDENTIALS_GIVEN);
		}
	}

}
?>
