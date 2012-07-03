<?php
namespace Kyoki\OAuth2\Security\Authorization\Voter;

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

/**
 * An access decision voter, that asks the FLOW3 PolicyService for a decision.
 *
 * @FLOW3\Scope("singleton")
 */
class Policy implements \TYPO3\FLOW3\Security\Authorization\AccessDecisionVoterInterface {

	/**
	 * The policy service
	 * @var \TYPO3\FLOW3\Security\Policy\PolicyService
	 */
	protected $policyService;

	/**
	 * Constructor.
	 *
	 * @param \TYPO3\FLOW3\Security\Policy\PolicyService $policyService The policy service
	 */
	public function __construct(\TYPO3\FLOW3\Security\Policy\PolicyService $policyService) {
		$this->policyService = $policyService;
	}

	/**
	 * This is the default Policy voter, it votes for the access privilege for the given join point
	 *
	 * @param \TYPO3\FLOW3\Security\Context $securityContext The current securit context
	 * @param \TYPO3\FLOW3\Aop\JoinPointInterface $joinPoint The joinpoint to vote for
	 * @return integer One of: VOTE_GRANT, VOTE_ABSTAIN, VOTE_DENY
	 */
	public function voteForJoinPoint(\TYPO3\FLOW3\Security\Context $securityContext, \TYPO3\FLOW3\Aop\JoinPointInterface $joinPoint) {
		$accessGrants = 0;
		$accessDenies = 0;
		$tokens = $securityContext->getAuthenticationTokens();
		foreach ($tokens as $token) {
			if ($token instanceof \Kyoki\OAuth2\Security\Authentication\Token\AccessTokenHttpBasic) {
				break;
			}
			return self::VOTE_ABSTAIN;
		}
		/**
		 * @var $token \Kyoki\OAuth2\Security\Authentication\Token\AccessTokenHttpBasic
		 * @var $scope \Kyoki\OAuth2\Domain\Model\OAuthScope
		 */
		$scope = $token->getOauthToken()->getOauthCode()->getOauthScope();
		try {
			$privileges = $this->policyService->getPrivilegesForJoinPoint(new \TYPO3\FLOW3\Security\Policy\Role($scope->getId()), $joinPoint);
		} catch (\TYPO3\FLOW3\Security\Exception\NoEntryInPolicyException $e) {
			return self::VOTE_DENY;
		}

		foreach ($privileges as $privilege) {
			if ($privilege === \TYPO3\FLOW3\Security\Policy\PolicyService::PRIVILEGE_GRANT) {
				$accessGrants++;
			} elseif ($privilege === \TYPO3\FLOW3\Security\Policy\PolicyService::PRIVILEGE_DENY) {
				$accessDenies++;
			}
		}

		if ($accessDenies > 0) {
			return self::VOTE_DENY;
		}
		if ($accessGrants > 0) {
			return self::VOTE_ABSTAIN;
		}

		return self::VOTE_DENY;
	}

	/**
	 * This is the default Policy voter, it votes for the access privilege for the given resource
	 *
	 * @param \TYPO3\FLOW3\Security\Context $securityContext The current securit context
	 * @param string $resource The resource to vote for
	 * @return integer One of: VOTE_GRANT, VOTE_ABSTAIN, VOTE_DENY
	 */
	public function voteForResource(\TYPO3\FLOW3\Security\Context $securityContext, $resource) {
		return self::VOTE_ABSTAIN;
	}
}

?>