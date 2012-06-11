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
abstract class OAuthAbstractController extends \TYPO3\FLOW3\Mvc\Controller\ActionController
{

    /**
     * Mainly for managin extensions
     *
     * @param \TYPO3\FLOW3\Mvc\RequestInterface $request
     * @param \TYPO3\FLOW3\Mvc\ResponseInterface $response
     */
    public function processRequest(\TYPO3\FLOW3\Mvc\RequestInterface $request, \TYPO3\FLOW3\Mvc\ResponseInterface $response)
    {
        try
        {
            parent::processRequest($request, $response);
        } catch (\TYPO3\FLOW3\Mvc\Exception\RequiredArgumentMissingException $ex)
        {
            // TODO soportar mas tipos de error y mostrarlos mejor con un template
            echo  json_encode(
                array(
                    'error' => 'server_error',
                    'error_message' => $ex->getMessage()
                ));
        } catch (OAuthException $ex)
        {
            // TODO soportar mas tipos de error y mostrarlos mejor con un template
            echo  json_encode(
                array(
                    'error' => 'server_error',
                    'error_message' => $ex->getMessage()
                ));
        } catch (\TYPO3\FLOW3\Property\Exception $ex) {
            // TODO soportar mas tipos de error y mostrarlos mejor con un template
            echo  json_encode(
                array(
                    'error' => 'server_error',
                    'error_message' => 'Exception while property mapping'
                ));
        }
    }
}