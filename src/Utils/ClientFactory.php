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

class ClientFactory
{
    /**
     * Create an instance of {Aws\AwsClientInterface} class for handling connections to AWS SES services.
     *
     * @return \Aws\AwsClientInterface
     */
    public static function createClient(array $params, $type = 'ses')
    {
        switch (strtolower($type)) {
            case 'ses':
                return new \Aws\Ses\SesClient(array_merge(['version' => '2010-12-01'], $params ?? []));
            case 'pinpoint':
                return new \Aws\Pinpoint\PinpointClient(array_merge(['version' => '2010-12-01'], $params ?? []));
            default:
                throw new \RuntimeException('Unimplement client type'.(string) $type);
        }
    }
}
