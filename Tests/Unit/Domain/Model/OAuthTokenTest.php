<?php
namespace Kyoki\OAuth2\Tests\Unit\Domain\Model;
/**
 * Created by JetBrains PhpStorm.
 * User: fernando
 * Date: 30/06/12
 * Time: 16:29
 * To change this template use File | Settings | File Templates.
 */
class OAuthTokenTest extends \TYPO3\FLOW3\Tests\UnitTestCase {

	protected $oauthCode;

	public function setUp() {
		$account = $this->getMock('TYPO3\FLOW3\Security\Account');
		$client = new \Kyoki\OAuth2\Domain\Model\OAuthClient($account,'a description', 'http:\\something');
		$scope = new \Kyoki\OAuth2\Domain\Model\OAuthScope('myscope');
		$this->oauthCode = new \Kyoki\OAuth2\Domain\Model\OAuthCode($client,$account,$scope);
	}

	/**
	 * @test
	 */
	public function accessTokenIsInitialized() {
		$oauthToken = new \Kyoki\OAuth2\Domain\Model\OAuthToken($this->oauthCode,30,\Kyoki\OAuth2\Domain\Model\OAuthToken::TOKENTYPE_BEARER);
		$this->assertGreaterThan(0,strlen($oauthToken->getAccessToken()));
	}

	/**
	 * @test
	 */
	public function refreshTokenIsInitialized() {
		$oauthToken = new \Kyoki\OAuth2\Domain\Model\OAuthToken($this->oauthCode,30,\Kyoki\OAuth2\Domain\Model\OAuthToken::TOKENTYPE_BEARER);
		$this->assertGreaterThan(0,strlen($oauthToken->getRefreshToken()));
	}

	/**
	 * @test
	 */
	public function accessTokenInitializedRandom() {
		$values = array();
		for ($i=1; $i<=100;$i++) {
			$oauthToken = new \Kyoki\OAuth2\Domain\Model\OAuthToken($this->oauthCode,30,\Kyoki\OAuth2\Domain\Model\OAuthToken::TOKENTYPE_BEARER);
			$values[] = $oauthToken->getAccessToken();
		}
		$this->assertTrue(count(array_unique($values))==100);
	}

	/**
	 * @test
	 */
	public function refreshTokenInitializedRandom() {
		$values = array();
		for ($i=1; $i<=100;$i++) {
			$oauthToken = new \Kyoki\OAuth2\Domain\Model\OAuthToken($this->oauthCode,30,\Kyoki\OAuth2\Domain\Model\OAuthToken::TOKENTYPE_BEARER);
			$values[] = $oauthToken->getRefreshToken();
		}
		$this->assertTrue(count(array_unique($values))==100);
	}

	/**
	 * @test
	 */
	public function creationDateIsInitilizedToNow() {
		$oauthToken = new \Kyoki\OAuth2\Domain\Model\OAuthToken($this->oauthCode,30,\Kyoki\OAuth2\Domain\Model\OAuthToken::TOKENTYPE_BEARER);
		$now = new \DateTime();
		// precission in seconds
		$this->assertEquals($oauthToken->getCreationDate()->getTimestamp(), $now->getTimestamp());
	}
}
