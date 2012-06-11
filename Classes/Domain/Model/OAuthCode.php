<?php
namespace Kyoki\OAuth2\Domain\Model;
/*                                                                        *
 * This script belongs to the Kyoki.OAuth2 package.                        *
 * @author Fernando Arconada <fernando.arconada@gmail.com>                *
 *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 *                                                                        */
use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;
use Kyoki\OAuth2\Domain\Model\OAuthClient;
use Kyoki\OAuth2\Domain\Model\OAuthScope;
use TYPO3\Party\Domain\Model\AbstractParty;
/**
 * An OAuth consumer
 *
 * @FLOW3\Entity
 */
class OAuthCode
{
    /**
     * @var \Kyoki\OAuth2\Domain\Model\OAuthClient
     * @ORM\ManyToOne
     */
    protected $oauthClient;

    /**
     * @FLOW3\Identity
     * @ORM\Id
     * @var string
     */
    protected $code;

	/**
	 * @var boolean
	 */
	protected $enabled;

    /**
     * @var \Kyoki\OAuth2\Domain\Model\OAuthScope
     * @ORM\ManyToOne
     */
    protected $oauthScope;

	/**
	 * @var string
	 */
	protected $redirectUri;

	/**
	* @var \TYPO3\Party\Domain\Model\AbstractParty
	* @ORM\ManyToOne
	*/
    protected $party;

    /**
     * @var \Doctrine\Common\Collections\Collection<\Kyoki\OAuth2\Domain\Model\OAuthToken>
     * @ORM\OneToMany(mappedBy="oauthCode")
     */
    protected $tokens;


	public function __construct(OAuthClient $OAuthClient, AbstractParty $party, OAuthScope $scope) {
		$secret = sha1(bin2hex(\TYPO3\FLOW3\Utility\Algorithms::generateRandomBytes(96)));
		$this->code = $secret ;
		$this->enabled = FALSE;
		$this->setOauthClient($OAuthClient);
		$this->setParty($party);
		$this->setOauthScope($scope);
	}


	/**
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	public function setOauthClient($oauthClient) {
		$this->oauthClient = $oauthClient;
	}

	public function getOauthClient() {
		return $this->oauthClient;
	}

	protected function setOauthScope($oauthScope) {
		$this->oauthScope = $oauthScope;
	}

	public function getOauthScope() {
		return $this->oauthScope;
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $party
	 */
	protected  function setParty($party) {
		$this->party = $party;
	}

	/**
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	public function getParty() {
		return $this->party;
	}

	/**
	 * @param string $redirectUri
	 */
	public function setRedirectUri($redirectUri) {
		$this->redirectUri = $redirectUri;
	}

	/**
	 * @return string
	 */
	public function getRedirectUri() {
		return $this->redirectUri;
	}

	/**
	 * @param boolean $enabled
	 */
	public function setEnabled($enabled) {
		$this->enabled = $enabled;
	}

	/**
	 * @return boolean
	 */
	public function getEnabled() {
		return $this->enabled;
	}


    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTokens()
    {
        return $this->tokens;
    }

}
