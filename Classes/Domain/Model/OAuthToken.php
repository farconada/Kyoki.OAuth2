<?php
namespace Kyoki\OAuth2\Domain\Model;
/**
 * Created by JetBrains PhpStorm.
 * User: fernando
 * Date: 28/04/12
 * Time: 13:15
 * To change this template use File | Settings | File Templates.
 */
use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * An OAuth consumer
 *
 * @FLOW3\Entity
 */
class OAuthToken
{
    /**
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
	 * @var int
	 */
	protected $expiresIn;


    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param \DateTime $lastUsed
     */
    public function setLastUsed($lastUsed)
    {
        $this->lastUsed = $lastUsed;
    }

    /**
     * @return \DateTime
     */
    public function getLastUsed()
    {
        return $this->lastUsed;
    }

    /**
     * @param \TYPO3\Party\Domain\Model\AbstractParty $party
     */
    public function setParty($party)
    {
        $this->party = $party;
    }

    /**
     * @return \TYPO3\Party\Domain\Model\AbstractParty
     */
    public function getParty()
    {
        return $this->party;
    }

    /**
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $token
     */
    public function setAccessToken($token)
    {
        $this->accessToken = $token;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

	/**
	 * @param string $refreshToken
	 */
	public function setRefreshToken($refreshToken) {
		$this->refreshToken = $refreshToken;
	}

	/**
	 * @return string
	 */
	public function getRefreshToken() {
		return $this->refreshToken;
	}

	/**
	 * @param \Kyoki\OAuth2\Domain\Model\OAuthApi $api
	 */
	public function setApi($api) {
		$this->api = $api;
	}

	/**
	 * @return \Kyoki\OAuth2\Domain\Model\OAuthApi
	 */
	public function getApi() {
		return $this->api;
	}
}