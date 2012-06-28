<?php
namespace OAuth2\GrantType;

use OAuth2\InvalidArgumentException;

/**
 * Refresh Token  Parameters
 */
class AccessToken implements IGrantType
{
    /**
     * Defines the Grant Type
     *
     * @var string  Defaults to 'refresh_token'.
     */
    const GRANT_TYPE = 'access_token';

    /**
     * Adds a specific Handling of the parameters
     *
     * @return array of Specific parameters to be sent.
     * @param  mixed  $parameters the parameters array (passed by reference)
     */
    public function validateParameters(&$parameters)
    {
        if (!isset($parameters['access_token']))
        {
            throw new InvalidArgumentException(
                'The \'access_token\' parameter must be defined for the access token grant type',
                InvalidArgumentException::MISSING_PARAMETER
            );
        }
    }
}
