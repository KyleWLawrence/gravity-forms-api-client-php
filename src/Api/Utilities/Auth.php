<?php

namespace KyleWLawrence\GForms\Api\Utilities;

use KyleWLawrence\GForms\Api\Exceptions\AuthException;
use Psr\Http\Message\RequestInterface;

/**
 * Class Auth
 * This helper would manage all Authentication related operations.
 */
class Auth
{
    /**
     * The authentication setting to use a Bearer API Token.
     */
    const BASIC = 'basic';

    /**
     * @var string
     */
    protected $authStrategy;

    /**
     * @var array
     */
    protected $authOptions;

    /**
     * Returns an array containing the valid auth strategies
     *
     * @return array
     */
    protected static function getValidAuthStrategies()
    {
        return [self::BASIC];
    }

    /**
     * Auth constructor.
     *
     * @param    $strategy
     * @param  array  $options
     *
     * @throws AuthException
     */
    public function __construct($strategy, array $options)
    {
        if (! in_array($strategy, self::getValidAuthStrategies())) {
            throw new AuthException('Invalid auth strategy set, please use `'
                                    .implode('` or `', self::getValidAuthStrategies())
                                    .'`');
        }

        $this->authStrategy = $strategy;

        if ($strategy == self::BASIC) {
            if (! array_key_exists('username', $options)) {
                throw new AuthException('Please supply `username` for Basic auth.');
            } elseif (! array_key_exists('password', $options)) {
                throw new AuthException('Please supply `password` for Basic auth.');
            }
        }

        $this->authOptions = $options;
    }

    /**
     * @param  RequestInterface  $request
     * @param  array  $requestOptions
     * @return array
     *
     * @throws AuthException
     */
    public function prepareRequest(RequestInterface $request, array $requestOptions = [])
    {
        if ($this->authStrategy === self::BASIC) {
            $username = $this->authOptions['username'];
            $password = $this->authOptions['password'];
            $request = $request->withAddedHeader('Authorization', 'Basic '.base64_encode("{$username}:{$password}"));
        } else {
            throw new AuthException('Please set authentication to send requests.');
        }

        return [$request, $requestOptions];
    }
}
