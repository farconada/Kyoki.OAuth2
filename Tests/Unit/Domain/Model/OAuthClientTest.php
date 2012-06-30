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
	/**
	 * @test
	 */
	public function clientIdIsInitialized() {
		$oauthClient = new \Kyoki\OAuth2\Domain\Model\OAuthClient('a description', 'http:\\something');
		$this->assertGreaterThan(0,strlen($oauthClient->getClientId()));
	}

	/**
	 * @test
	 */
	public function clientSecretIsInitialized() {
		$oauthClient = new \Kyoki\OAuth2\Domain\Model\OAuthClient('a description', 'http:\\something');
		$this->assertGreaterThan(0,strlen($oauthClient->getSecret()));
	}

	/**
	 * @test
	 */
	public function descriptionIsInitialized() {
		$oauthClient = new \Kyoki\OAuth2\Domain\Model\OAuthClient('a description', 'http:\\something');
		$this->assertEquals('a description',$oauthClient->getDescription());
	}

	/**
	 * @test
	 */
	public function redirectUriIsInitialized() {
		$oauthClient = new \Kyoki\OAuth2\Domain\Model\OAuthClient('a description', 'http:\\something');
		$this->assertEquals('http:\\something',$oauthClient->getRedirectUri());
	}

	/**
	 * @test
	 */
	public function clientIdIsInitializedRandom() {
		$ids = array();
		for ($i=1; $i<=100;$i++) {
			$oauthClient = new \Kyoki\OAuth2\Domain\Model\OAuthClient('a description', 'http:\\something');
			$ids[] = $oauthClient->getClientId();
		}
		$this->assertTrue(count(array_unique($ids))==100);
	}

	/**
	 * @test
	 */
	public function clientSecretIsInitializedRandom() {
		$secret = array();
		for ($i=1; $i<=100;$i++) {
			$oauthClient = new \Kyoki\OAuth2\Domain\Model\OAuthClient('a description', 'http:\\something');
			$secret[] = $oauthClient->getSecret();
		}
		$this->assertTrue(count(array_unique($secret))==100);
	}
}
