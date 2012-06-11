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
