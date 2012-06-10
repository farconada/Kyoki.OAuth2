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
use Kyoki\OAuth2\Domain\Model\OAuthCode;
/**
 * Repository for parties
 *
 * @FLOW3\Scope("singleton")
 */
class OAuthCodeRepository extends \TYPO3\FLOW3\Persistence\Repository {

    /**
     * @param OAuthCode $oauthCode
     */
    public function removeCodeTokens(OAuthCode $oauthCode) {
        $tokens = $oauthCode->getTokens();
        foreach ($tokens as $token) {
            $this->persistenceManager->remove($token);
        }
        $this->persistenceManager->persistAll();
    }
}