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

namespace Drewlabs\Envoyer\Drivers\Aws\Contracts;

use Drewlabs\Envoyer\Contracts\ClientSecretKeyAware;
use Drewlabs\Envoyer\Contracts\ServerConfigInterface;

interface ServerInterface extends ServerConfigInterface, ClientSecretKeyAware
{
    /**
     * Return the AWS SES notification server region.
     *
     * @return string
     */
    public function getServerRegion();
}
