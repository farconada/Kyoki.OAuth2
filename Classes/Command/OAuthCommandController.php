<?php
namespace Kyoki\OAuth2\Command;
/**
 * Created by JetBrains PhpStorm.
 * User: fernando
 * Date: 11/05/12
 * Time: 13:07
 * To change this template use File | Settings | File Templates.
 */
use TYPO3\FLOW3\Annotations as FLOW3;
use Kyoki\OAuth2\Domain\Model\OAuthClient;
use Kyoki\OAuth2\Domain\Model\OAuthScope;

/**
 *   @FLOW3\Scope("singleton")
 */
class OAuthCommandController extends   \TYPO3\FLOW3\Cli\CommandController {

	/**
	 * @var \Kyoki\OAuth2\Domain\Repository\OAuthClientRepository
	 * @FLOW3\Inject
	 */
	protected $oauthClientRepository;

	/**
	 * @var \Kyoki\OAuth2\Domain\Repository\OAuthScopeRepository
	 * @FLOW3\Inject
	 */
	protected $oauthScopeRepository;

	/**
	 * @param string $description
	 * @param string $redirect_uri
	 */
	public function newClientCommand($description, $redirect_uri) {
		$oauthClient = new OAuthClient($description, $redirect_uri);
		$this->oauthClientRepository->add($oauthClient);
		echo 'Cliente creado, Id: ' . $oauthClient->getClientId(), ' Secret: ' . $oauthClient->getSecret();
	}

	/**
	 * @param string $id
	 * @param string $description
	 */
	public function newScopeCommand($id, $description) {
		$oauthScope = new OAuthScope($id, $description);
		$this->oauthScopeRepository->add($oauthScope);
		echo 'Scope creado, Id: ' . $oauthScope->getId() . ' Description: ' . $oauthScope->getDescription();
	}
}