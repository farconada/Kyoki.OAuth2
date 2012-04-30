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
class OAuthApi
{
    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $callbackUrl;

    /**
     * @var string
     */
    protected $desccription;

    /**
     * @var \Kyoki\OAuth2\Domain\Model\OAuthConsumer
     * @ORM\OneToMany(mappedBy="api")
     */
    protected $consumer;


    /**
     * @param string $callbackUrl
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
    }

    /**
     * @return string
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

    /**
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $desccription
     */
    public function setDesccription($desccription)
    {
        $this->desccription = $desccription;
    }

    /**
     * @return string
     */
    public function getDesccription()
    {
        return $this->desccription;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }
}
