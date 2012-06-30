<?php
namespace Kyoki\OAuth2\Tests\Unit\Security\Authentication\Provider;
/**
 * Created by JetBrains PhpStorm.
 * User: fernando
 * Date: 30/06/12
 * Time: 10:09
 * To change this template use File | Settings | File Templates.
 */
class AccessTokenProviderTest extends \TYPO3\FLOW3\Tests\UnitTestCase {

	const ACCESS_TOKEN = '765i76b5vbti76tituy';


	/**
	 * Test provider authentication against a valid token
	 * @test
	 */
	public function authenticatingValidAccessToken() {

		$mockAccount = $this->getMock('TYPO3\FLOW3\Security\Account', array(), array(), '', FALSE);

		$mockParty = $this->getMock('TYPO3\Party\Domain\Model\Person', array(), array(), '', FALSE);
		$partyAccounts = new \Doctrine\Common\Collections\ArrayCollection();
		$partyAccounts->add($mockAccount);
		$mockParty->expects($this->once())->method('getAccounts')->will($this->returnValue($partyAccounts));

		$mockOAuthCode = $this->getMock('Kyoki\OAuth2\Domain\Model\OAuthCode',array(),array(),'',FALSE);
		$mockOAuthCode->expects($this->once())->method('getParty')->will($this->returnValue($mockParty));

		$mockOAuthToken = $this->getMock('Kyoki\OAuth2\Domain\Model\OAuthToken',array(),array(),'',FALSE);
		$mockOAuthToken->expects($this->once())->method('getCreationDate')->will($this->returnValue(new \DateTime()));
		$mockOAuthToken->expects($this->once())->method('getOAuthCode')->will($this->returnValue($mockOAuthCode));

		$mockOAuthTokenRepository = $this->getMock('Kyoki\OAuth2\Domain\Repository\OAuthTokenRepository', array(), array(), '', FALSE);
		$mockOAuthTokenRepository->expects($this->once())->method('findByIdentifier')->with(self::ACCESS_TOKEN)->will($this->returnValue($mockOAuthToken));

		$mockToken = $this->getMock('Kyoki\OAuth2\Security\Authentication\Token\AccessTokenHttpBasic', array(), array(), '', FALSE);
		$mockToken->expects($this->once())->method('getCredentials')->will($this->returnValue(array('access_token' => self::ACCESS_TOKEN)));
		$mockToken->expects($this->once())->method('setAuthenticationStatus')->with(\TYPO3\FLOW3\Security\Authentication\TokenInterface::AUTHENTICATION_SUCCESSFUL);
		$mockToken->expects($this->once())->method('setAccount')->with($mockAccount);

		$accessTokenProvider = $this->getAccessibleMock('Kyoki\OAuth2\Security\Authentication\Provider\AccessTokenProvider', array('dummy'), array('myProvider', array()));
		$accessTokenProvider->_set('oauthTokenRepository', $mockOAuthTokenRepository);

		$accessTokenProvider->authenticate($mockToken);

		return $mockToken->getAuthenticationStatus();
	}


	/**
	 * Test provider authentication against an expired token
	 * @test
	 */
	public function authenticatingExpiredAccessToken() {

		$mockOAuthToken = $this->getMock('Kyoki\OAuth2\Domain\Model\OAuthToken',array(),array(),'',FALSE);
		$mockOAuthToken->expects($this->once())->method('getExpiresIn')->will($this->returnValue('30'));
		$date = new \DateTime();
		$date->modify('-31 seconds');
		$mockOAuthToken->expects($this->once())->method('getCreationDate')->will($this->returnValue($date));

		$mockOAuthTokenRepository = $this->getMock('Kyoki\OAuth2\Domain\Repository\OAuthTokenRepository', array(), array(), '', FALSE);
		$mockOAuthTokenRepository->expects($this->once())->method('findByIdentifier')->with(self::ACCESS_TOKEN)->will($this->returnValue($mockOAuthToken));

		$mockToken = $this->getMock('Kyoki\OAuth2\Security\Authentication\Token\AccessTokenHttpBasic', array(), array(), '', FALSE);
		$mockToken->expects($this->once())->method('getCredentials')->will($this->returnValue(array('access_token' => self::ACCESS_TOKEN)));
		$mockToken->expects($this->once())->method('setAuthenticationStatus')->with(\TYPO3\FLOW3\Security\Authentication\TokenInterface::WRONG_CREDENTIALS);

		$accessTokenProvider = $this->getAccessibleMock('Kyoki\OAuth2\Security\Authentication\Provider\AccessTokenProvider', array('dummy'), array('myProvider', array()));
		$accessTokenProvider->_set('oauthTokenRepository', $mockOAuthTokenRepository);

		$accessTokenProvider->authenticate($mockToken);

		return $mockToken->getAuthenticationStatus();
	}

}
