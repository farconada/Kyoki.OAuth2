<?php
namespace Kyoki\OAuth2\Tests\Unit\Domain\Model;
/**
 * Created by JetBrains PhpStorm.
 * User: fernando
 * Date: 30/06/12
 * Time: 16:29
 * To change this template use File | Settings | File Templates.
 */
class OAuthCodeTest extends \TYPO3\FLOW3\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function codeIsInitialized() {
		$account = $this->getMock('TYPO3\FLOW3\Security\Account');
		$client = new \Kyoki\OAuth2\Domain\Model\OAuthClient($account,'a description', 'http:\\something');
	    $scope = new \Kyoki\OAuth2\Domain\Model\OAuthScope('myscope');
		$oauthCode = new \Kyoki\OAuth2\Domain\Model\OAuthCode($client,$account,$scope);
		$this->assertGreaterThan(0,strlen($oauthCode->getCode()));
	}

	/**
	 * @test
	 */
	public function codeInitializedRandom() {
		$account = $this->getMock('TYPO3\FLOW3\Security\Account');
		$secret = array();
		for ($i=1; $i<=100;$i++) {
			$client =  new \Kyoki\OAuth2\Domain\Model\OAuthClient($account,'a description', 'http:\\something');
			$scope = new \Kyoki\OAuth2\Domain\Model\OAuthScope('myscope');
			$oauthCode = new \Kyoki\OAuth2\Domain\Model\OAuthCode($client,$account,$scope);
			$secret[] = $oauthCode->getCode();
		}
		$this->assertTrue(count(array_unique($secret))==100);
	}

	/**
	 * @test
	 */
	public function codeIsDisabledByDefault() {
		$account = $this->getMock('TYPO3\FLOW3\Security\Account');
		$client =  new \Kyoki\OAuth2\Domain\Model\OAuthClient($account,'a description', 'http:\\something');
		$scope = new \Kyoki\OAuth2\Domain\Model\OAuthScope('myscope');
		$oauthCode = new \Kyoki\OAuth2\Domain\Model\OAuthCode($client,$account,$scope);
		$this->assertFalse($oauthCode->getEnabled());
	}
}
