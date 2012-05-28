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
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/*
	* @var array
	*/
	protected $settings;

	/**
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * @param string $description
	 * @param string $redirect_uri
	 */
	public function newClientCommand($description, $redirect_uri, $partyUUID=NULL) {
		$oauthClient = new OAuthClient($description, $redirect_uri);
		if (!$partyUUID) {
			$party = $this->persistenceManager->getObjectByIdentifier($this->settings['Client']['DefaultPartyUUID'],$this->settings['Client']['PartyClassName']);
		} else {
			$party = $this->persistenceManager->getObjectByIdentifier($partyUUID,$this->settings['Client']['PartyClassName']);
		}
		$oauthClient->setParty($party);
		$this->oauthClientRepository->add($oauthClient);
		$this->persistenceManager->persistAll();

		echo 'Cliente creado, Id: ' . $oauthClient->getClientId(), ' Secret: ' . $oauthClient->getSecret() . "\n";
	}

	/**
	 * @param string $id
	 * @param string $description
	 */
	public function newScopeCommand($id, $description) {
		$oauthScope = new OAuthScope($id, $description);
		$this->oauthScopeRepository->add($oauthScope);
		$this->persistenceManager->persistAll();
		echo 'Scope creado, Id: ' . $oauthScope->getId() . ' Description: ' . $oauthScope->getDescription(). "\n";
	}
}