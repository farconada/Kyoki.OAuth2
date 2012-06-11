<?php
namespace Kyoki\OAuth2\Domain\Repository;
/*                                                                        *
 * This script belongs to the Kyoki.OAuth2 package.                        *
 * @author Fernando Arconada <fernando.arconada@gmail.com>                *
 *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;
use Kyoki\OAuth2\Domain\Model\OAuthCode;

/**
 * Repository for parties
 *
 * @FLOW3\Scope("singleton")
 */
class OAuthCodeRepository extends \TYPO3\FLOW3\Persistence\Repository
{

    /**
     * @param OAuthCode $oauthCode
     */
    public function removeCodeTokens(OAuthCode $oauthCode)
    {
        $tokens = $oauthCode->getTokens();
        foreach ($tokens as $token)
        {
            $this->persistenceManager->remove($token);
        }
        $this->persistenceManager->persistAll();
    }
}