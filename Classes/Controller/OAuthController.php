<?php
namespace Kyoki\OAuth2\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Kyoki.OAuth2".               *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;
use Kyoki\OAuth2\Domain\Model\OAuthClient;
use Kyoki\OAuth2\Domain\Model\OAuthScope;
use Kyoki\OAuth2\Domain\Model\OAuthCode;
use Kyoki\OAuth2\Exception\OAuthException;

/**
 * OAuth controller for the Kyoki.OAuth2 package
 *
 * @FLOW3\Scope("singleton")
 */
class OAuthController extends \TYPO3\FLOW3\Mvc\Controller\ActionController
{
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
	 * @param string $response_type
	 * @param Kyoki\OAuth2\Domain\Model\OAuthClient $client_id
	 * @param string $redirect_uri
	 * @param Kyoki\OAuth2\Domain\Model\OAuthScope $scope
	 */
	public function authorizeAction($response_type,OAuthClient $client_id, $redirect_uri, OAuthScope $scope) {
		if (!preg_match('/' . urlencode($client_id->getRedirectUri()) . '/', urlencode($redirect_uri))) {
			throw new OAuthException('La URL de redireccion no concuerda con las autorizdaad',1337249067);
		}
		$oauthCode = new OAuthCode($client_id,$this->securityContext->getParty(),$scope);
		switch ($response_type) {
		    case 'code':
		        $this->oauthCodeRepository->add($oauthCode);
		        $this->persistenceManager->persistAll();
		        $this->view->assign('oauthCode', $oauthCode);
		        $this->view->assign('oauthScope', $scope);
		        break;
		    case 'refresh':
		        // TODO implementa refresh
			    throw new OAuthException('Response Type no implentado',1337249155);
		        break;
			default:
				throw new OAuthException('Response Type no implentado', 1337249132);
		}


	}

	/**
	 * @param \Kyoki\OAuth2\Domain\Model\OAuthCode $oauthCode
	 */
	public function grantAction(OAuthCode $oauthCode){
		$oauthCode->setEnabled(TRUE);
		$this->oauthCodeRepository->update($oauthCode);
		$this->redirectToUri($oauthCode->getRedirectUri() . '?' . http_build_query(array('code' => $oauthCode->getCode()), null,'&'));
	}

	public function denyAction(){
		$this->view->assign('message', 'Acceso no concedido');
	}
}