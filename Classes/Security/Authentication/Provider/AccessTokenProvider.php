<?php
namespace Kyoki\OAuth2\Security\Authentication\Provider;

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
 * An authentication provider that authenticates
 * TYPO3\FLOW3\Security\Authentication\Token\UsernamePassword tokens.
 * The accounts are stored in the Content Repository.
 */
class ClientIdSecretProvider extends \TYPO3\FLOW3\Security\Authentication\Provider\AbstractProvider {

	/**
	 * @var \Kyoki\OAuth2\Domain\Repository\OAuthCodeRepository
	 * @FLOW3\Inject
	 */
	protected $oauthTokenRepository;


	/**
	 * Returns the class names of the tokens this provider can authenticate.
	 *
	 * @return array
	 */
	public function getTokenClassNames() {
		return array('Kyoki\OAuth2\Security\Authentication\Token\AccessTokenHttpBasic');
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

		if (is_array($credentials) && isset($credentials['access_token'])) {
			/**
			 * @var $oauthToken \Kyoki\OAuth2\Domain\Model\OAuthToken;
			 */
			$oauthToken = $this->oauthTokenRepositoryRepository->findByIdentifier($credentials['access_token']);
		}

		if (is_object($oauthToken)) {
			//TODO verificar scope
			$now = new \DateTime();
			if (($oauthToken->getCreationDate()->getTimestamp()+$oauthToken->getExpiresIn()) < $now->getTimestamp()) {
				$authenticationToken->setAuthenticationStatus(\TYPO3\FLOW3\Security\Authentication\TokenInterface::WRONG_CREDENTIALS);
			}
			$authenticationToken->setAuthenticationStatus(\TYPO3\FLOW3\Security\Authentication\TokenInterface::AUTHENTICATION_SUCCESSFUL);
			//$authenticationToken->setAccount($oauthCode->getParty()->getAccounts()->first());
		} elseif ($authenticationToken->getAuthenticationStatus() !== \TYPO3\FLOW3\Security\Authentication\TokenInterface::AUTHENTICATION_SUCCESSFUL) {
			$authenticationToken->setAuthenticationStatus(\TYPO3\FLOW3\Security\Authentication\TokenInterface::NO_CREDENTIALS_GIVEN);
		}
	}

}
?>
