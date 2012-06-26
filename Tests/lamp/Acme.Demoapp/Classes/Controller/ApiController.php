<?php
namespace Acme\Demoapp\Controller;
use TYPO3\FLOW3\Annotations as FLOW3;

/*                                                                        *
* This script belongs to the FLOW3 package "F2.TuitLawyer".              *
*                                                                        *
*                                                                        */

/**
 * Standard controller for the F2.TuitLawyer package
 *
 * @FLOW3\Scope("singleton")
 */
class ApiController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {
    public function resourceAction(){
        $this->response->setContent(json_encode(array('something' => 'secured')));
	    $this->response->setHeader('Content-Type', 'application/json');
    }
}

?>