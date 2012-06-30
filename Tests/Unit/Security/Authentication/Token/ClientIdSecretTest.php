<?php
namespace Kyoki\OAuth2\Tests\Unit\Security\Authentication\Token;
/**
 * Created by JetBrains PhpStorm.
 * User: fernando
 * Date: 30/06/12
 * Time: 14:04
 * To change this template use File | Settings | File Templates.
 */
class ClientIdSecretTest extends \TYPO3\FLOW3\Tests\UnitTestCase {
	const CLIENT_ID = 'AAAAAAAAAAA';
	const CLIENT_SECRET = 'BBBBBBBBBB';

	/**
	 * @test
	 */
	public function haveCredentialsByPost() {
		$mockHttpRequest = $this->getMock('TYPO3\FLOW3\Http\Request', array(), array(),'',FALSE);
		$mockHttpRequest->expects($this->once())->method('getMethod')->will($this->returnValue('POST'));

		$mockActionRequest = $this->getMock('TYPO3\FLOW3\Mvc\ActionRequest', array(), array(),'',FALSE);
		$mockActionRequest->expects($this->once())->method('getHttpRequest')->will($this->returnValue($mockHttpRequest));
		$mockActionRequest->expects($this->once())->method('getArguments')
			->will($this->returnValue(array('client_id' => self::CLIENT_ID, 'client_secret' => self::CLIENT_SECRET)));


		$clientIdSecretToken = $this->getMock('Kyoki\OAuth2\Security\Authentication\Token\ClientIdSecret', array('dummy'), array(),'',FALSE);
		$clientIdSecretToken->updateCredentials($mockActionRequest);
		$credentials = $clientIdSecretToken->getCredentials();
		$this->assertEquals(self::CLIENT_ID,$credentials['client_id']);
		$this->assertEquals(self::CLIENT_SECRET,$credentials['client_secret']);
		$this->assertEquals(\Kyoki\OAuth2\Security\Authentication\Token\ClientIdSecret::AUTHENTICATION_NEEDED,$clientIdSecretToken->getAuthenticationStatus());
	}

	/**
	 * @test
	 */
	public function notAuthenticationWithoutPost() {
		$httpMethods = array('GET','PUT');
		foreach ($httpMethods as $method) {
			$mockHttpRequest = $this->getMock('TYPO3\FLOW3\Http\Request', array(), array(),'',FALSE);
			$mockHttpRequest->expects($this->once())->method('getMethod')->will($this->returnValue($method));

			$mockActionRequest = $this->getMock('TYPO3\FLOW3\Mvc\ActionRequest', array(), array(),'',FALSE);
			$mockActionRequest->expects($this->once())->method('getHttpRequest')->will($this->returnValue($mockHttpRequest));


			$clientIdSecretToken = $this->getMock('Kyoki\OAuth2\Security\Authentication\Token\ClientIdSecret', array('dummy'), array(),'',FALSE);
			$clientIdSecretToken->updateCredentials($mockActionRequest);
			$credentials = $clientIdSecretToken->getCredentials();
			$this->assertEquals('',$credentials['client_id']);
			$this->assertEquals('',$credentials['client_secret']);
			$this->assertEquals(\Kyoki\OAuth2\Security\Authentication\Token\ClientIdSecret::NO_CREDENTIALS_GIVEN,$clientIdSecretToken->getAuthenticationStatus());
		}

	}
}
