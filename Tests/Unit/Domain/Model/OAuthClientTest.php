<?php
namespace Kyoki\OAuth2\Tests\Unit\Domain\Model;
/**
 * Created by JetBrains PhpStorm.
 * User: fernando
 * Date: 30/06/12
 * Time: 16:29
 * To change this template use File | Settings | File Templates.
 */
class OAuthClientTest extends \TYPO3\FLOW3\Tests\UnitTestCase {
	public $oauthClient;

	public function setUp() {
		$account = $this->getMock('TYPO3\FLOW3\Security\Account');
		$this->oauthClient = new \Kyoki\OAuth2\Domain\Model\OAuthClient($account,'a description', 'http:\\something');
	}
	/**
	 * @test
	 */
	public function clientIdIsInitialized() {
		$this->assertGreaterThan(0,strlen($this->oauthClient->getClientId()));
	}

	/**
	 * @test
	 */
	public function clientSecretIsInitialized() {
		$this->assertGreaterThan(0,strlen($this->oauthClient->getSecret()));
	}

	/**
	 * @test
	 */
	public function descriptionIsInitialized() {
		$this->assertEquals('a description',$this->oauthClient->getDescription());
	}

	/**
	 * @test
	 */
	public function redirectUriIsInitialized() {
		$this->assertEquals('http:\\something',$this->oauthClient->getRedirectUri());
	}

	/**
	 * @test
	 */
	public function clientIdIsInitializedRandom() {
		$ids = array();
		$account = $this->getMock('TYPO3\FLOW3\Security\Account');
		for ($i=1; $i<=100;$i++) {
			$oauthClient = new \Kyoki\OAuth2\Domain\Model\OAuthClient($account,'a description', 'http:\\something');
			$ids[] = $oauthClient->getClientId();
		}
		$this->assertTrue(count(array_unique($ids))==100);
	}

	/**
	 * @test
	 */
	public function clientSecretIsInitializedRandom() {
		$secret = array();
		$account = $this->getMock('TYPO3\FLOW3\Security\Account');
		for ($i=1; $i<=100;$i++) {
			$oauthClient = new \Kyoki\OAuth2\Domain\Model\OAuthClient($account,'a description', 'http:\\something');
			$secret[] = $oauthClient->getSecret();
		}
		$this->assertTrue(count(array_unique($secret))==100);
	}
}
