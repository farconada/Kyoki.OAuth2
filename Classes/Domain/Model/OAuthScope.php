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
class OAuthScope
{
    /**
     * @FLOW3\Identity
     * @ORM\Id
     * @FLOW3\Validate(type="Text")
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $description;

	public function __construct($id, $description='') {
		$this->id =$id ;
		$this->setDescription($description);
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


	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

}
