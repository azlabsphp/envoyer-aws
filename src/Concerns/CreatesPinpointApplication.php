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

namespace Drewlabs\Envoyer\Drivers\Aws\Concerns;

use Drewlabs\Envoyer\Drivers\Aws\PinpointApplication;
use Aws\Pinpoint\PinpointClient;

trait CreatesPinpointApplication
{
    
    /**
     * @var PinpointClient
     */
    private $client;

    public function createApplication(string $name, array $tags = [])
    {
        $result = $this->client->createApp([
            'CreateApplicationRequest' => [
                'Name' => $name,
                'tags' => $tags ?? [],
            ],
        ]);

        return PinpointApplication::fromAWSResult($result);
    }
}
