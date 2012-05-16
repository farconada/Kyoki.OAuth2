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
class OAuthClient
{
    /**
     * @ORM\Id
     * @FLOW3\Identity
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $redirectUri;

    /**
     * @var string
     */
    protected $description;

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ORM\ManyToOne
	 */
	protected $party;


	public function __construct($description, $redirectUri) {
		$clientId =  sha1(bin2hex(\TYPO3\FLOW3\Utility\Algorithms::generateRandomBytes(96)));
		$secret = sha1(bin2hex(\TYPO3\FLOW3\Utility\Algorithms::generateRandomBytes(96)));
		$this->setSecret($secret);
		$this->clientId = $clientId;
		$this->setDescription($description);
		$this->setRedirectUri($redirectUri);
	}


	/**
	 * @return string
	 */
	public function getClientId() {
		return $this->clientId;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	public function setParty($party) {
		$this->party = $party;
	}

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
	 * @param string $secret
	 */
	public function setSecret($secret) {
		$this->secret = $secret;
	}

	/**
	 * @return string
	 */
	public function getSecret() {
		return $this->secret;
	}


}
