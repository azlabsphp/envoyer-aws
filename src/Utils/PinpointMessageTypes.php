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

class PinpointMessageTypes
{
    /**
     * Transactional message type for time-sensitive content.
     *
     * @var string
     */
    public const TRANSACTIONAL = 'TRANSACTIONAL';

    /**
     * Promotional message type for marketing based contents.
     *
     * @var string
     */
    public const PROMOTIONAL = 'PROMOTIONAL';

    /**
     * List of Pinpoint message types.
     *
     * @var string[]
     */
    public const VALUES = ['TRANSACTIONAL', 'PROMOTIONAL'];
}
