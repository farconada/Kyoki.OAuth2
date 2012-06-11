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
use Kyoki\OAuth2\Domain\Model\OAuthCode;

/**
 * An OAuth consumer
 *
 * @FLOW3\Entity
 */
class OAuthToken
{
    /**
     * @FLOW3\Identity
     * @ORM\Id
     * @var string
     * @FLOW3\Validate(type="NotEmpty")
     */
    protected $accessToken;

	/**
	 * @var string
	 * @FLOW3\Validate(type="NotEmpty")
	 */
	protected $refreshToken;

    /**
     * @var \DateTime
     */
    protected $creationDate;

	/**
	 * @var string
	 */
	protected $tokenType;

	/**
     * Seconds
	 * @var int
	 */
	protected $expiresIn;

    /**
     * @var \Kyoki\OAuth2\Domain\Model\OAuthCode
     * @ORM\OneToOne
     */
    protected $oauthCode;


    public function __construct(OAuthCode $OAuthCode, $expiresIn, $tokenType) {
        $access = sha1(bin2hex(\TYPO3\FLOW3\Utility\Algorithms::generateRandomBytes(96)));
        $this->setAccessToken($access);
        $refresh = sha1(bin2hex(\TYPO3\FLOW3\Utility\Algorithms::generateRandomBytes(96)));
        $this->setRefreshToken($refresh);
        $this->setCreationDate(new \DateTime());
        $this->setExpiresIn($expiresIn);
        $this->setTokenType($tokenType);
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param \DateTime $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param int $expiresIn
     */
    public function setExpiresIn($expiresIn)
    {
        $this->expiresIn = $expiresIn;
    }

    /**
     * @return int
     */
    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    /**
     * @param \Kyoki\OAuth2\Domain\Model\OAuthCode $oauthCode
     */
    public function setOauthCode($oauthCode)
    {
        $this->oauthCode = $oauthCode;
    }

    /**
     * @return \Kyoki\OAuth2\Domain\Model\OAuthCode
     */
    public function getOauthCode()
    {
        return $this->oauthCode;
    }

    /**
     * @param string $refreshToken
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @param string $tokenType
     */
    public function setTokenType($tokenType)
    {
        $this->tokenType = $tokenType;
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

}