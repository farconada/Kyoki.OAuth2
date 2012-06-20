<?php
namespace Kyoki\OAuth2\Controller;

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
use Kyoki\OAuth2\Domain\Model\OAuthClient;
use Kyoki\OAuth2\Domain\Model\OAuthScope;
use Kyoki\OAuth2\Domain\Model\OAuthCode;
use Kyoki\OAuth2\Exception\OAuthException;
use Kyoki\OAuth2\Controller\OAuthAbstractController;

/**
 * OAuth controller for the Kyoki.OAuth2 package
 * Manages the permission request and exchanges an OAuth Code
 *
 * @FLOW3\Scope("singleton")
 */
class OAuthController extends OAuthAbstractController {
	/**
	 * @var \TYPO3\FLOW3\Security\Context
	 * @FLOW3\Inject
	 */
	protected $securityContext;


	/**
	 * @var \Kyoki\OAuth2\Domain\Repository\OAuthCodeRepository
	 * @FLOW3\Inject
	 */
	protected $oauthCodeRepository;

	/**
	 * Authenticate and request permission
	 *
	 * @FLOW3\SkipCsrfProtection
	 * @param string $response_type
	 * @param Kyoki\OAuth2\Domain\Model\OAuthClient $client_id persistence identifier
	 * @param string $redirect_uri
	 * @param Kyoki\OAuth2\Domain\Model\OAuthScope $scope persistence identifier
	 */
	public function authorizeAction($response_type, OAuthClient $client_id, $redirect_uri, OAuthScope $scope) {
		if (!preg_match('/' . urlencode($client_id->getRedirectUri()) . '/', urlencode($redirect_uri))) {
			throw new OAuthException('La URL de redireccion no concuerda con las autorizada', 1337249067);
		}
		$oauthCode = new OAuthCode($client_id, $this->securityContext->getParty(), $scope);
		$oauthCode->setRedirectUri($redirect_uri);
		if ($response_type == 'code') {
			$this->oauthCodeRepository->add($oauthCode);
			$this->persistenceManager->persistAll();
			$this->view->assign('oauthCode', $oauthCode);
			$this->view->assign('oauthScope', $scope);
		} else {
			throw new OAuthException('Response Type not implemented', 1337249132);
		}
	}

	/**
	 * Access granted, return an OAuth Code
	 *
	 * @param \Kyoki\OAuth2\Domain\Model\OAuthCode $oauthCode
	 */
	public function grantAction(OAuthCode $oauthCode) {
		$oauthCode->setEnabled(TRUE);
		$this->oauthCodeRepository->update($oauthCode);
		$this->redirectToUri($oauthCode->getRedirectUri() . '?' . http_build_query(array('code' => $oauthCode->getCode()), null, '&'));
	}

	/**
	 * Access denied
	 *
	 * @param \Kyoki\OAuth2\Domain\Model\OAuthCode $oauthCode
	 */
	public function denyAction(OAuthCode $oauthCode) {
		$this->redirectToUri($oauthCode->getRedirectUri() . '?' . http_build_query(array('error' => 'access_denied'), null, '&'));
		$this->oauthCodeRepository->remove($oauthCode);
	}


}