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
	 * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-28 4.1.1
	 */
	const RESPONSETYPE_CODE = 'code';

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
	 * @return void
	 * @throws \Kyoki\OAuth2\Exception\OAuthException
	 */
	public function authorizeAction($response_type, OAuthClient $client_id, $redirect_uri, OAuthScope $scope) {
		// the first part of the $redirect_url string match the authorized redirect_url in the client object
		$isInAuthorizedUrl = strpos(urlencode(strtolower($redirect_uri)), urlencode(strtolower($client_id->getRedirectUri())));
		if ($isInAuthorizedUrl === FALSE || $isInAuthorizedUrl != 0 ) {
			throw new OAuthException('This redirect_url is not authorized', 1337249067);
		}
		$oauthCode = new OAuthCode($client_id, $this->securityContext->getAccount(), $scope);
		$oauthCode->setRedirectUri($redirect_uri);
		if ($response_type === self::RESPONSETYPE_CODE) {
			$this->oauthCodeRepository->add($oauthCode);
			$this->persistenceManager->persistAll();
			$this->view->assign('oauthCode', $oauthCode);
			$this->view->assign('oauthScope', $scope);
		} else {
			throw new OAuthException(sprintf('Response Type "%s" not implemented', $response_type), 1337249132);
		}
	}

	/**
	 * Access granted, return an OAuth Code
	 *
	 * @param \Kyoki\OAuth2\Domain\Model\OAuthCode $oauthCode
	 * @return void
	 */
	public function grantAction(OAuthCode $oauthCode) {
		$oauthCode->setEnabled(TRUE);
		$this->oauthCodeRepository->update($oauthCode);
		$this->redirectToUri($this->appendToUserUrl($oauthCode->getRedirectUri(),array('code' => $oauthCode->getCode())));
	}

	/**
	 * Access denied
	 *
	 * @param \Kyoki\OAuth2\Domain\Model\OAuthCode $oauthCode
	 * @return void
	 */
	public function denyAction(OAuthCode $oauthCode) {
		$this->redirectToUri($this->appendToUserUrl($oauthCode->getRedirectUri(),array('error' => 'access_denied')),0,403);
		$this->oauthCodeRepository->remove($oauthCode);
	}

	/**
	 * Appends some paraters to a URL given by the user input
	 *
	 * @param string $userUrl  User URL
	 * @param array $parameters asociative array of name=value parameters to be appended
	 * @return string
	 */
	private function appendToUserUrl($userUrl, $parameters) {
		$urlComponents = parse_url($userUrl);
		if (!isset($urlComponents['query'])) {
			if (substr($userUrl,-1) === '?') {
				return $userUrl . http_build_query($parameters, null, '&');
			}
			return $userUrl . '?' . http_build_query($parameters, null, '&');
		} else {
			return $userUrl . '&' . http_build_query($parameters, null, '&');
		}
	}


}