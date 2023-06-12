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

interface ApplicationInterface
{
    /**
     * returns the application name.
     */
    public function getName(): string;

    /**
     * returns the applicartion `id`.
     *
     * @return string|int
     */
    public function getId(): string;
}
