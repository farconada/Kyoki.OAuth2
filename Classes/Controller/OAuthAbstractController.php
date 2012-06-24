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
use Kyoki\OAuth2\Exception\OAuthException;

/**
 * Abstract class for controllers
 * It is used to manage exceptions
 *
 * @FLOW3\Scope("singleton")
 */
abstract class OAuthAbstractController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {

	/**
	 * Mainly for managin extensions
	 *
	 * @param \TYPO3\FLOW3\Mvc\RequestInterface $request
	 * @param \TYPO3\FLOW3\Mvc\ResponseInterface $response
	 * @return void
	 */
	public function processRequest(\TYPO3\FLOW3\Mvc\RequestInterface $request, \TYPO3\FLOW3\Mvc\ResponseInterface $response) {
		try {
			parent::processRequest($request, $response);
		} catch (\TYPO3\FLOW3\Mvc\Exception\RequiredArgumentMissingException $ex) {
			$this->setErrorResponse($response,$ex->getMessage());
		} catch (OAuthException $ex) {
			$this->setErrorResponse($response,$ex->getMessage());
		} catch (\TYPO3\FLOW3\Property\Exception $ex) {
			$this->setErrorResponse($response,'Exception while property mapping');
		}
	}

	/**
	 * Sets error content in the response object
	 *
	 * @param ResponseInterface $response
	 * @param $message
	 */
	private function setErrorResponse(\TYPO3\FLOW3\Mvc\ResponseInterface $response, $message) {
		$response->setContent(json_encode(
			array(
				'error' => 'server_error',
				'error_message' => $message
			)));
		if ($response instanceof \TYPO3\FLOW3\Http\Response) {
			/**
			 * @var $response \TYPO3\FLOW3\Http\Response
			 */
			$response->setHeader('Content-Type', 'application/json');
			$response->setStatus(500);
		}

	}
}