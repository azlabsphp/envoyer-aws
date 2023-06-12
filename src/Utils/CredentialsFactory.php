<?php

declare(strict_types=1);

/*
 * This file is part of the drewlabs namespace.
 *
 * (c) Sidoine Azandrew <azandrewdevelopper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Drewlabs\Envoyer\Drivers\Aws\Utils;

use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\RejectedPromise;

class CredentialsFactory
{
    /**
     * This method creates authentication credentials from configuration parameters.
     *
     * @param string $key
     * @param string $secret
     *
     * @return \Closure|PromiseInterface
     */
    public static function create($key, $secret, $token = null)
    {
        if (null === $key || null === $secret || !\is_string($key) || !\is_string($secret)) {
            return new RejectedPromise(new \Aws\Exception\CredentialsException('aws keys and credentials are not defined'));
        }
        // This function IS the credential provider
        return static function () use ($key, $secret, $token) {
            // Use credentials from environment variables, if available
            if ($key && $secret) {
                return Create::promiseFor(new \Aws\Credentials\Credentials($key, $secret, $token));
            }

            $msg = 'Could not find environment variable credentials in '.$key.'/'.$secret;

            return new RejectedPromise(new \Aws\Exception\CredentialsException($msg));
        };
    }
}
