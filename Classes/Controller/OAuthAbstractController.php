<?php
namespace Kyoki\OAuth2\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Kyoki.OAuth2".               *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;
use Kyoki\OAuth2\Exception\OAuthException;

/**
 * OAuth controller for the Kyoki.OAuth2 package
 *
 * @FLOW3\Scope("singleton")
 */
abstract class OAuthAbstractController extends \TYPO3\FLOW3\Mvc\Controller\ActionController
{

	/**
	 * Mainly for managin extensions
	 *
	 * @param \TYPO3\FLOW3\Mvc\RequestInterface $request
	 * @param \TYPO3\FLOW3\Mvc\ResponseInterface $response
	 */
	public function processRequest(\TYPO3\FLOW3\Mvc\RequestInterface $request, \TYPO3\FLOW3\Mvc\ResponseInterface $response) {
		try {
			parent::processRequest($request, $response);
		} catch (\Exception $ex) {
			// TODO soportar mas tipos de error y mostrarlos mejor con un template
			echo  json_encode(array('error' => 'server_error'));
		}

	}

}