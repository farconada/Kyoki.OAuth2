<?php
namespace Kyoki\OAuth2\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Kyoki.OAuth2".               *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;
use \Kyoki\OAuth2\Service\OAuthService;

/**
 * Standard controller for the Kyoki.OAuth2 package
 *
 * @FLOW3\Scope("singleton")
 */
class StandardController extends \TYPO3\FLOW3\Mvc\Controller\ActionController
{

    /**
     * @FLOw3\Inject
     * @var \Kyoki\OAuth2\Service\OAuthService
     */
    protected $oauthService;

    /**
     * @var \Kyoki\OAuth2\Domain\Repository\OAuthConsumerRepository
     */
    protected $oauthConsumerRepository;


    /**
     * Index action
     *
     * @return void
     */
    public function indexAction()
    {
    }

    private function identifyUser($auth_header)
    {
        // identify the user
        $oauth_pieces = explode(' ', $auth_header);
        if (count($oauth_pieces) <> 2)
        {
            throw new Exception('Invalid Authorization Header', '400');
        }
        if (strtolower($oauth_pieces[0]) != "oauth")
        {
            throw new Exception('Unknown Authorization Header Received', '400');
        }
        return $this->oauth_service->verifyAccessToken($oauth_pieces[1]);
    }

    public function processRequest(\TYPO3\FLOW3\Mvc\RequestInterface $request, \TYPO3\FLOW3\Mvc\ResponseInterface $response)
    {
        if ($request->isMainRequest())
        {
            /**
             * @var \TYPO3\FLOW3\Mvc\ActionRequest $request
             */
            $headers = $request->getHttpRequest()->getHeaders();
            /**
             * @var \TYPO3\FLOW3\Http\Headers $headers
             */
            if ($headers->has('Authorization'))
            {
                $user_id = $this->oauthService->getUserIdFromAccessToken($headers->get('Authorization'));
            }
        }
        parent::processRequest($request, $response);
    }

    /**
     * Action to redirect to grant or deny
     *
     * @param string $isAuthorized
     *
     * @return void
     */
    public function authorizeAction($isAuthorized = NULL)
    {
        if (!$isAuthorized) {
            $accessToken = $this->oauthService->computeAccessToken();
            $this->redirect('grant', NULL, NULL, array('access_token' => $accessToken));
        } else {
            $this->redirect('deny');
        }
    }

    /**
     * Action to execute if granted
     *
     * @param string $accesstoken The access token to be returned to the client
     *
     * @return void
     */
    public function grantAction($accesstoken,$scope,$description)
    {


    }

    /**
     * Permission denied
     *
     * @return void
     */
    public function denyAction()
    {

    }



}

?>