<?php
namespace Kyoki\OAuth2\Service;
/**
 * Created by JetBrains PhpStorm.
 * User: fernando
 * Date: 26/04/12
 * Time: 18:00
 * To change this template use File | Settings | File Templates.
 */

class OAuthService {

    /**
     * @var \Kyoki\OAuth2\Domain\Repository\OAuthConsumerRepository
     */
    protected $oauthConsumerRepository;

    public function verifyAccessToken($token) {

    }

    public function updateLastUsedToken($token) {
        $now = new \DateTime();
        /**
         * @var $oauthConsumer \Kyoki\OAuth2\Domain\Model\OAuthConsumer
         */
        $oauthConsumer = $this->oauthConsumerRepository->findByIdentifier($token);
        $oauthConsumer->setLastUsed($now);
    }

    /**
     * @return string The access token string
     */
    public function computeAccessToken() {

    }

    /**
     * @param string $accessToken
     *
     * @return string userId
     */
    public function getUserIdFromAccessToken($accessToken) {

    }
}