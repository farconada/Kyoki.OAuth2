<?php
namespace Acme\Demoapp\Domain\Model;
use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * A User
 *
 * @FLOW3\Scope("prototype")
 * @FLOW3\Entity
 */
class User extends \TYPO3\Party\Domain\Model\AbstractParty {

}
