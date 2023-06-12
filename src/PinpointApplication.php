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

namespace Drewlabs\Envoyer\Drivers\Aws;

use Aws\Result;
use Drewlabs\Envoyer\Drivers\Aws\Contracts\ApplicationInterface;

class PinpointApplication implements ApplicationInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string|null
     */
    private $arn;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string[]
     */
    private $tags;

    /**
     * Creates class instance.
     *
     * @param mixed $arn
     */
    public function __construct(string $id, string $name, array $tags = [], $arn = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->tags = $tags ?? [];
        $this->arn = $arn;
    }

    /**
     * Creates new application instance from AWS result instance.
     *
     * @return static
     */
    public static function fromAWSResult(Result $result)
    {
        return new static($result->get('Id'), $result->get('Name'), $result->get('tags'), $result->get('Arn'));
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return (string) $this->id;
    }

    public function getArn()
    {
        return $this->arn;
    }

    /**
     * returns the list of application tags.
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }
}
