<?php
namespace Kyoki\OAuth2\Aspect;
use TYPO3\FLOW3\Annotations as FLOW3;
use Kyoki\OAuth2\Exception\OAuthException;

/**
 * Created by JetBrains PhpStorm.
 * User: fernando
 * Date: 17/10/11
 * Time: 19:05
 * To change this template use File | Settings | File Templates.
 */
/**
 * @FLOW3\Aspect
 */
class ParametersAspect {

    /**
     * @var \TYPO3\FLOW3\Security\Context
     * @FLOW3\Inject
     */
    protected $securityContext;

    /**
     * PointCut token endpoint
     *
     * @FLOW3\Pointcut("method(Kyoki\OAuth2\Controller\TokenController->tokenAction())")
     * @return void
     */
    public function tokenParameters() {
    }

    /**
     * PointCut token endpoint
     *
     * @FLOW3\Pointcut("method(Kyoki\OAuth2\Controller\OAuthController->grantAction())")
     * @return void
     */
    public function allOAuthControllerMethods() {
    }

     /**
     * @param \TYPO3\FLOW3\Aop\JoinPointInterface $joinPoint
     * @return void
     * @FLOW3\Before("Kyoki\OAuth2\Aspect\ParametersAspect->tokenParameters")
     */
    public function validateTokenParameters(\TYPO3\FLOW3\Aop\JoinPointInterface $joinPoint) {
        $arguments = $joinPoint->getMethodArguments();
        if ($arguments['grant_type'] != 'refresh_token' && $arguments['grant_type'] != 'authorization_code') {
            throw new OAuthException('unsupported grant type', 1338317418);
        }
        if ($arguments['grant_type'] == 'authorization_code' && !$arguments['code']) {
            throw new OAuthException('code not set', 1338317419);
        }
    }

    /**
     * @param \TYPO3\FLOW3\Aop\JoinPointInterface $joinPoint
     * @return void
     * @FLOW3\Before("Kyoki\OAuth2\Aspect\ParametersAspect->allOAuthControllerMethods")
     */
    public function validateOAuthCode(\TYPO3\FLOW3\Aop\JoinPointInterface $joinPoint) {
        if ($joinPoint->isMethodArgument('code')) {
            /**
             * @var $code \Kyoki\OAuth2\Domain\Model\OAuthCode;
             */
            $code = $joinPoint->getMethodArgument('code');
            if ($this->securityContext->getParty() != $code->getParty()) {
                throw new OAuthException('You are not the owner of this security code', 1338318722);
            }
        }
    }


}