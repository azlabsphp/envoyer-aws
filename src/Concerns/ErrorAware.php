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

trait ErrorAware
{
    /**
     * @var \Throwable
     */
    private $error;

    /**
     * Checks if the result has error property set.
     *
     * @return bool
     */
    public function hasError()
    {
        return null !== $this->error;
    }

    /**
     * return the error (exception class) if it's set.
     *
     * @return \Throwable|null
     */
    public function getError()
    {
        return $this->error;
    }
}
