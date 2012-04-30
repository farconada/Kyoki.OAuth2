<?php
namespace Kyoki\OAuth2\Domain\Repository;
/**
 * Created by JetBrains PhpStorm.
 * User: fernando
 * Date: 28/04/12
 * Time: 13:24
 * To change this template use File | Settings | File Templates.
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use \TYPO3\Party\Domain\Model\AbstractParty;
/**
 * Repository for parties
 *
 * @FLOW3\Scope("singleton")
 */
class OAuthConsumerRepository extends \TYPO3\FLOW3\Persistence\Repository {


    /**
     * @param \TYPO3\Party\Domain\Model\AbstractParty $party
     */
    public function findTokensByParty (AbstractParty $party) {
        $query = $this->createQuery();
        $query = $query->matching($query->equals('party', $party));
        return $query->execute();
    }

}