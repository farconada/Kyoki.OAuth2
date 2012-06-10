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
class OAuthTokenRepository extends \TYPO3\FLOW3\Persistence\Repository {
    public function findByRefreshToken($refresh_token) {
        $query = $this->createQuery();
        $query = $query->matching($query->equals('refreshToken', $refresh_token));
        return $query->execute();

    }

    public function findTokensByCode(OAuthCode $oauthCode) {
        $query = $this->createQuery();
        $query = $query->matching($query->equals('oauthCode', $oauthCode));
        return $query->execute();
    }
}