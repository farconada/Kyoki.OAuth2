<?php
namespace Kyoki\OAuth2\Tests\Unit\Security\Authentication\Provider;
/**
 * Created by JetBrains PhpStorm.
 * User: fernando
 * Date: 30/06/12
 * Time: 10:09
 * To change this template use File | Settings | File Templates.
 */
class ClientIdSecretProviderTest extends \TYPO3\FLOW3\Tests\UnitTestCase {

	const CLIENT_ID = '11111111';
	const CLIENT_SECRET = '2222222';

	/**
	 * @test
	 */
	public function authenticateWithClientIdSecretHttpBasicToken() {
		$mockToken = $this->getMock('Kyoki\OAuth2\Security\Authentication\Token\ClientIdSecretHttpBasic', array(), array(), '', FALSE);
		$this->authenticatingClientIdSecret($mockToken);
	}

	/**
	 * @test
	 */
	public function authenticateWithClientIdSecretPostToken() {
		$mockToken = $this->getMock('Kyoki\OAuth2\Security\Authentication\Token\ClientIdSecret', array(), array(), '', FALSE);
		$this->authenticatingClientIdSecret($mockToken);
	}

	/**
	 * Test that Provider returns AUTHENTICATION_SUCCESSFUL and sets the account right
	 * @param $mockToken
	 */
	public function authenticatingClientIdSecret($mockToken) {

		$mockAccount = $this->getMock('TYPO3\FLOW3\Security\Account', array(), array(), '', FALSE);

		$mockParty = $this->getMock('TYPO3\Party\Domain\Model\Person', array(), array(), '', FALSE);
		$partyAccounts = new \Doctrine\Common\Collections\ArrayCollection();
		$partyAccounts->add($mockAccount);
		$mockParty->expects($this->once())->method('getAccounts')->will($this->returnValue($partyAccounts));

		$mockOAuthClient = $this->getMock('Kyoki\OAuth2\Domain\Model\OAuthClient',array(),array(),'',FALSE);
	    $mockOAuthClient->expects($this->once())->method('getParty')->will($this->returnValue($mockParty));
		$mockOAuthClient->expects($this->once())->method('getSecret')->will($this->returnValue(self::CLIENT_SECRET));



		$mockOAuthClientRepository = $this->getMock('Kyoki\OAuth2\Domain\Repository\OAuthClientRepository', array(), array(), '', FALSE);
		$mockOAuthClientRepository->expects($this->once())->method('findByIdentifier')->with(self::CLIENT_ID)->will($this->returnValue($mockOAuthClient));

		$mockToken->expects($this->once())->method('getCredentials')->will($this->returnValue(array('client_id' => self::CLIENT_ID, 'client_secret' => self::CLIENT_SECRET)));
		$mockToken->expects($this->once())->method('setAuthenticationStatus')->with(\TYPO3\FLOW3\Security\Authentication\TokenInterface::AUTHENTICATION_SUCCESSFUL);
		$mockToken->expects($this->once())->method('setAccount')->with($mockAccount);

		$usernamePasswordCRProvider = $this->getAccessibleMock('Kyoki\OAuth2\Security\Authentication\Provider\ClientIdSecretProvider', array('dummy'), array('myProvider', array()));
		$usernamePasswordCRProvider->_set('oauthClientRepository', $mockOAuthClientRepository);

		$usernamePasswordCRProvider->authenticate($mockToken);
	}

}
