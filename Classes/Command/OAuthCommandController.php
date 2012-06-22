<?php
namespace Kyoki\OAuth2\Command;
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

/**
 * This command provides some helper c¡cmd actions like:
 *   - create an OAuthScope
 *   - create an OAuthClient
 *
 * @FLOW3\Scope("singleton")
 */
class OAuthCommandController extends \TYPO3\FLOW3\Cli\CommandController {

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

	/**
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
	 * Creates a new OAuth Client from command line
	 *
	 * @param $description
	 * @param $redirectUri
	 * @param null $partyUuid The party persistence identifier that the OAuthClient blengs to
	 */
	public function newClientCommand($description, $redirectUri, $partyUuid = NULL) {
		$oauthClient = new OAuthClient($description, $redirectUri);
		if (!$partyUuid) {
			$party = $this->persistenceManager->getObjectByIdentifier($this->settings['Client']['DefaultPartyUUID'], $this->settings['Client']['PartyClassName']);
		} else {
			$party = $this->persistenceManager->getObjectByIdentifier($partyUuid, $this->settings['Client']['PartyClassName']);
		}
		$oauthClient->setParty($party);
		$this->oauthClientRepository->add($oauthClient);
		$this->persistenceManager->persistAll();
		
		$this->outputLine('Something in english, Id: "%s", Secret: "%s"', array($oauthClient->getClientId(), $oauthClient->getSecret());
	}

	/**
	 * Creates a new OAuth Scope from command line
	 *
	 * @param string $id Scope id, should match withy a Role id
	 * @param string $description [BW] Please add a description for $description ;)
	 */
	public function newScopeCommand($id, $description) {
		$oauthScope = new OAuthScope($id, $description);
		$this->oauthScopeRepository->add($oauthScope);
		$this->persistenceManager->persistAll();
		// [BW] Use $this->output() or $this->outputLine(). See above
		echo 'Scope creado, Id: ' . $oauthScope->getId() . ' Description: ' . $oauthScope->getDescription() . "\n";
	}
}