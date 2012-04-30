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
class OAuthConsumer
{
    /**
     * @FLOW3\Identity
     * @ORM\Id
     * @var string
     * @FLOW3\Validate(type="NotEmpty")
     */
    protected $token;

    /**
     * @var \DateTime
     */
    protected $lastUsed;


    /**
     * @var \TYPO3\Party\Domain\Model\AbstractParty
     * @ORM\ManyToOne
     * @FLOW3\Validate(type="NotEmpty")
     */
    protected $party;

    /**
     * @var \Kyoki\OAuth2\Domain\Model\OAuthApi
     * @ORM\ManyToOne
     * @FLOW3\Validate(type="NotEmpty")
     */
    protected $api;

    /**
     * @var string
     */
    protected $scope;

    /**
     * @var string
     */
    protected $description;

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
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}