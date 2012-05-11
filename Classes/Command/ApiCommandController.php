<?php
namespace Kyoki\OAuth2\Command;
/**
 * Created by JetBrains PhpStorm.
 * User: fernando
 * Date: 11/05/12
 * Time: 13:07
 * To change this template use File | Settings | File Templates.
 */
use TYPO3\FLOW3\Annotations as FLOW3;
use Kyoki\OAuth2\Domain\Model\OAuthApi;

/**
 *   @FLOW3\Scope("singleton")
 */
class ApiCommandController extends   \TYPO3\FLOW3\Cli\CommandController {

	/**
	 * @var \Kyoki\OAuth2\Domain\Repository\OAuthApiRepository
	 * @FLOW3\Inject
	 */
	protected $apiRepository;

	public function newApiAction() {
		$clientId = md5sum(bin2hex(\TYPO3\FLOW3\Utility\Algorithms::generateRandomBytes(96)));
		$api = new OAuthApi($clientId);
		$this->apiRepository->add($api);
		echo 'Creada API: client_id ' . $api->getClientId() .' secret ' . $api->getSecret();
	}
}