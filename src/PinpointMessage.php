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

use Drewlabs\Envoyer\Contracts\NotificationResult;
use Drewlabs\Envoyer\Drivers\Aws\Concerns\ErrorAware;

class PinpointMessage implements NotificationResult
{
    use ErrorAware;

    /**
     * @var string|\DateTimeInterface
     */
    private $createdAt;

    /**
     * @var string|int
     */
    private $id;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * Creates class instance.
     *
     * @return self
     */
    public function __construct($id = null, int $statusCode = 200, \DateTimeInterface $createdAt = null)
    {
        $this->id = $id;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->statusCode = $statusCode ?? 200;
    }

    /**
     * Creates result instance from aws API response.
     *
     * @return static
     */
    public static function fromAWSResult(\Aws\Result $result)
    {
        // TODO: Review implementations to query for correct key for metadata and message id
        $metadata = $result->get('@metadata');
        $id = $result->get('MessageId');
        $statusCode = \is_array($metadata) && isset($metadata['statusCode']) ? $metadata['statusCode'] : 200;
        $createdAt = $metadata && isset($metadata['headers']) && isset($metadata['headers']['date']) ? \DateTime::createFromFormat('D, j M Y H:i:s e', $metadata['headers']['date'])->format('Y-m-d H:i:s') : null;

        return new static($id, $statusCode, $createdAt);
    }

    /**
     * Create new error result instance.
     *
     * @return static
     */
    public static function exception(\Throwable $exception)
    {
        $instance = new static(null, (int) $exception->getCode(), new \DateTimeImmutable());
        $instance->error = $exception;

        return $instance;
    }

    public function date()
    {
        return $this->createdAt;
    }

    public function id()
    {
        return $this->id;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function isOk()
    {
        return $this->statusCode ? 500 !== $this->statusCode : false;
    }
}
