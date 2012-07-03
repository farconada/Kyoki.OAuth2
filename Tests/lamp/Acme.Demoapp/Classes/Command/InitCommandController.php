<?php
namespace Acme\Demoapp\Command;
/**
 * Created by JetBrains PhpStorm.
 * User: fernando
 * Date: 28/06/12
 * Time: 10:47
 * To change this template use File | Settings | File Templates.
 */


use TYPO3\FLOW3\Annotations as FLOW3;
use Kyoki\OAuth2\Domain\Model\OAuthClient as OAuthClient;
use Kyoki\OAuth2\Domain\Model\OAuthScope as OAuthScope;
use Acme\Demoapp\Domain\Model\User as User;
/**
 *
 * @FLOW3\Scope("singleton")
 */
class InitCommandController extends \TYPO3\FLOW3\Cli\CommandController {
	const USERNAME = 'username';
	const PASSWORD = 'password';
	const ROLE = 'User';
	const CLIENT_ID = 'AAAAAAAAAAAAAA';
	const CLIENT_SECRET = 'SSSSSSSSSSSSSSS';
	const SCOPE = 'myscope';
	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Security\AccountFactory
	 */
	protected $accountFactory;

	/**
	 * @FLOW3\Inject
	 * @var \Kyoki\OAuth2\Domain\Repository\OAuthClientRepository
	 */
	protected $clientRepository;

	/**
	 * @FLOW3\Inject
	 * @var \Kyoki\OAuth2\Domain\Repository\OAuthScopeRepository
	 */
	protected $scopeRepository;

	/**
	 * @FLOW3\Inject
	 * @var \Acme\Demoapp\Domain\Repository\UserRepository
	 */
	protected $userRepository;

	public function createAccountCommand() {
		if(!$this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName(self::USERNAME, 'DefaultProvider')) {
			$account = $this->accountFactory->createAccountWithPassword(self::USERNAME, self::PASSWORD, array(self::ROLE));
			$this->accountRepository->add($account);
			$this->outputLine('account created, username: "%s", password: "%s"', array(self::USERNAME, self::PASSWORD));
		}
	}

	public function createClientapiCommand() {
		if(!$this->clientRepository->findByIdentifier(self::CLIENT_ID)){
 			$account = $this->accountFactory->createAccountWithPassword(self::USERNAME.'-api-owner', self::PASSWORD, array(self::ROLE));
            $this->accountRepository->add($account);
			$client = new OAuthClient($account,'Demo API Client','http://');
			$client->setClientId(self::CLIENT_ID);
			$client->setSecret(self::CLIENT_SECRET);
			$this->clientRepository->add($client);
			$this->outputLine('API client created, client_id: "%s", client_secret: "%s"', array(self::CLIENT_ID, self::CLIENT_SECRET));
		}
	}

	public function createScopeCommand() {
		if(!$this->scopeRepository->findByIdentifier(self::SCOPE)){
			$scope = new OAuthScope(self::SCOPE,'Grant access to a demo resource');
			$this->scopeRepository->add($scope);
			$this->outputLine('scope created, id: "%s"', array(self::SCOPE));
		}	
	}
}
