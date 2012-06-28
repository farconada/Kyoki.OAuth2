<?php
namespace Acme\Demoapp\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Acme.Demoapp".               *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;
use Acme\Demoapp\Command\InitCommandController as InitCommandController;

/**
 * Standard controller for the Acme.Demoapp package 
 *
 * @FLOW3\Scope("singleton")
 */
class StandardController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {

	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Security\Context
	 */
	protected $securityContext;

	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('username', InitCommandController::USERNAME);
		$this->view->assign('password', InitCommandController::PASSWORD);
		$this->view->assign('clientId', InitCommandController::CLIENT_ID);
		$this->view->assign('clientSecret',InitCommandController::CLIENT_SECRET);
	}

	public function loginAction() {

	}

	/**
	 * Authenticates an account by invoking the Provider based Authentication Manager.
	 *
	 * Los parametros vienen del formulario de login de la Home
	 *
	 * @return void
	 */
	public function authenticateAction() {

		try {
			$this->authenticationManager->authenticate();
		} catch (\TYPO3\FLOW3\Security\Exception\AuthenticationRequiredException $exception) {
			$this->flashMessageContainer->addMessage(new \TYPO3\FLOW3\Error\Message('Wrong username or password.'));
			$this->redirect('login');
		}
		if ($interceptedRequest = $this->securityContext->getInterceptedRequest()) {
			$this->redirect($interceptedRequest->getControllerActionName(),
				$interceptedRequest->getControllerName(),
				$interceptedRequest->getControllerPackageKey(),
				$interceptedRequest->getArguments());
		} else {
			$this->redirect('index');
		}

	}

}

?>