<?php
namespace Kyoki\OAuth2\Controller;

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
use Kyoki\OAuth2\Domain\Model\OAuthToken;
use Kyoki\OAuth2\Exception\OAuthException;
use Kyoki\OAuth2\Controller\OAuthAbstractController;

/**
 * Token controller for the Kyoki.OAuth2 package
 * Manages de generation of Access Tokens and Refresh Tokens
 *
 * @FLOW3\Scope("singleton")
 */
class TokenController extends OAuthAbstractController
{
    /**
     * @var \Kyoki\OAuth2\Domain\Repository\OAuthTokenRepository
     * @FLOW3\Inject
     */
    protected $oauthTokenRepository;

    /**
     * @var \Kyoki\OAuth2\Domain\Repository\OAuthCodeRepository
     * @FLOW3\Inject
     */
    protected $oauthCodeRepository;


    /**
     * Token endpoint
     * Should be authenticated by client_id and client_secret
     *
     * @param string $grant_type
     * @param \Kyoki\OAuth2\Domain\Model\OAuthCode $code persistence identifier
     * @param string $refresh_token
     */
    public function tokenAction($grant_type, OAuthCode $code = NULL, $refresh_token = '')
    {
        if ($grant_type == 'refresh_token')
        {
            $token = $this->oauthTokenRepository->findByRefreshToken($refresh_token);
            if ($token)
            {
                $this->oauthTokenRepository->remove($token);

            }
        }
        $this->oauthCodeRepository->removeCodeTokens($code);
        $token = new OAuthToken($code, 3600, 'Bearer');
        $token->setOauthCode($code);
        $this->oauthTokenRepository->add($token);
        return json_encode(array(
            'access_token' => $token->getAccessToken(),
            'token_type' => $token->getTokenType(),
            'expires_in' => $token->getExpiresIn(),
            'refresh_token' => $token->getRefreshToken()
        ));
    }

}