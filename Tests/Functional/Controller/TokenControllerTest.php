<?php
namespace Kyoki\OAuth2\Tests\Functional\Controller;
/**
 * Created by JetBrains PhpStorm.
 * User: fernando
 * Date: 1/07/12
 * Time: 10:43
 * To change this template use File | Settings | File Templates.
 */
use TYPO3\FLOW3\Http\Client\Browser;
use TYPO3\FLOW3\Mvc\Routing\Route;
use Kyoki\OAuth2\Domain\Model\OAuthClient;
use Kyoki\OAuth2\Domain\Model\OAuthCode;
use Kyoki\OAuth2\Domain\Model\OAuthToken;
use Kyoki\OAuth2\Domain\Model\OAuthScope;
use TYPO3\Party\Domain\Model\Person;
use TYPO3\Party\Domain\Model\PersonName;

class TokenControllerTest extends \TYPO3\FLOW3\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	protected $testableHttpEnabled = TRUE;

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var boolean
	 */
	protected $testableSecurityEnabled = TRUE;

	/**
	 * @var \Kyoki\OAuth2\Domain\Model\OAuthCode
	 */
	protected $oauthCode;

	public function setUp() {
		parent::setUp();

		$route = new Route();
		$route->setName('TokenControllerTest Route 1');
		$route->setUriPattern('test/tokencontrollertest/{@action}');
		$route->setDefaults(array(
			'@package' => 'Kyoki.OAuth2',
			'@controller' => 'Token',
			'@format' =>'html'
		));
		$route->setAppendExceedingArguments(TRUE);
		$this->router->addRoute($route);
		$account = $this->authenticateRoles(array('OAuth'));
		$account->setAccountIdentifier('testIdentifier');
		$account->setAuthenticationProviderName('DefaultProvider');
		$this->persistenceManager->add($account);

		$oauthClientApi = new OAuthClient($account,'test API', 'http://');
		$this->persistenceManager->add($oauthClientApi);
		$oauthScope = new OAuthScope('myscope');
		$this->persistenceManager->add($oauthScope);
		$this->oauthCode = new OAuthCode($oauthClientApi,$account,$oauthScope);
		$this->oauthCode->setRedirectUri('http://consumerserver');
		$this->persistenceManager->add($this->oauthCode);

		$this->persistenceManager->persistAll();
	}

	/**
	 * @test
	 */
	public function oauthRoleIsRequired() {
	    $this->authenticateRoles(array());
	    $response = $this->browser->request('http://localhost/test/tokencontrollertest/token');
	    $this->assertEquals(403,$response->getStatusCode());
		$this->authenticateRoles(array('OAuth'));
		$response = $this->browser->request('http://localhost/test/tokencontrollertest/token');
		$this->assertNotEquals(403,$response->getStatusCode());
    }

	/**
	 * The content is json encoded and has application/json content-type
	 * @test
	 */
	public function tokenActionReturnsJsonContent() {
		$response = $this->browser->request('http://localhost/test/tokencontrollertest/token?grant_type=authorization_code&code='.$this->oauthCode->getCode());
		json_decode($response->getContent());
		$this->assertEquals(JSON_ERROR_NONE,json_last_error());
		$this->assertEquals('application/json',$response->getHeader('Content-Type'));
	}

	/**
	 * @test
	 */
	public function tokenActionReturnsBearerToken() {
		$response = $this->browser->request('http://localhost/test/tokencontrollertest/token?grant_type=authorization_code&code='.$this->oauthCode->getCode());
		$contentAsArray = json_decode($response->getContent(),TRUE);
		$this->assertEquals(\Kyoki\OAuth2\Domain\Model\OAuthToken::TOKENTYPE_BEARER,$contentAsArray['token_type']);

	}

	/**
	 * @test
	 */
	public function errorIfUnknownGrantType() {
		$response = $this->browser->request('http://localhost/test/tokencontrollertest/token?grant_type=XXXXXXX&code='.$this->oauthCode->getCode());
		$this->assertGreaterThanOrEqual(500,$response->getStatusCode());
		$contentAsArray = json_decode($response->getContent(),TRUE);
		$this->assertEquals('unsupported grant type',$contentAsArray['error_message']);
	}

	/**
	 * @test
	 */
	public function errorIfLoggedUserIsNotTheOwnerOfTheCode() {

	}


}
