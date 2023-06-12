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

use Aws\Pinpoint\Exception\PinpointException;
use Aws\Pinpoint\PinpointClient;
use Drewlabs\Envoyer\Contracts\ClientInterface;
use Drewlabs\Envoyer\Contracts\NotificationInterface;
use Drewlabs\Envoyer\Contracts\NotificationResult;
use Drewlabs\Envoyer\Drivers\Aws\Concerns\CreatesPinpointApplication;
use Drewlabs\Envoyer\Drivers\Aws\Contracts\PinpointClientInterface;
use Drewlabs\Envoyer\Drivers\Aws\Utils\PinpointMessageTypes;

class PinpointMessagesAdapter implements ClientInterface, PinpointClientInterface
{
    use CreatesPinpointApplication;

    /**
     * @var string
     */
    private $appId;

    /**
     * notification channel used by the pinpoint client.
     *
     * @var string
     */
    private $channel;

    /**
     * @var string[]
     */
    private $supportedChannels = [
        'PUSH',
        'GCM',
        'APNS',
        'APNS_SANDBOX',
        'APNS_VOIP',
        'APNS_VOIP_SANDBOX',
        'ADM',
        'SMS',
        'VOICE',
        'EMAIL',
        'BAIDU',
        'CUSTOM',
        'IN_APP',
    ];

    /**
     * @var string
     */
    private $messageType;

    /**
     * Creates class instance.
     */
    public function __construct(PinpointClient $client)
    {
        $this->client = $client;
    }

    /**
     * Creates new class instance.
     *
     * @param string $channel
     *
     * @throws RuntimeException
     *
     * @return self
     */
    public static function new(array $params, string $appId, ?string $channel = 'SMS')
    {
        $static = new static(new \Aws\Pinpoint\PinpointClient(array_merge(['version' => '2010-12-01'], $params ?? [])));

        // Set the pinpoint application id
        if (\is_string($appId)) {
            $static->setApp($appId);
        }

        // Set the pinpoint channel to use
        $static->setChannel($channel ?? 'SMS');

        // We returned the newly created instance
        return $static;
    }

    /**
     * Set pinpoint application.
     *
     * @return void
     */
    public function setApp(string $value)
    {
        $this->appId = $value;
    }

    /**
     * set pinpoint channel.
     *
     * @return void
     */
    public function setChannel(string $value)
    {
        if (!\in_array(strtoupper($value), $this->supportedChannels, true)) {
            throw new \InvalidArgumentException("Unsupported notification channel $value. Supported values are : ".implode(', ', $this->supportedChannels));
        }
        $this->channel = $value;
    }

    /**
     * Set the message type instance.
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function setMessageType(string $value)
    {
        if (!\in_array(strtoupper($value), PinpointMessageTypes::VALUES, true)) {
            throw new \InvalidArgumentException("Unsupported message type $value. Supported values are : ".implode(', ', PinpointMessageTypes::VALUES));
        }
        $this->messageType = $value;
    }

    public function sendRequest(NotificationInterface $instance): NotificationResult
    {
        try {
            $messageSender = $instance->getSender()->__toString();
            $message = '+' === substr((string) $messageSender, 0, 1) ? [
                'Body' => preg_replace("/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags((string) $instance->getContent())))),
                'MessageType' => $this->messageType ?? PinpointMessageTypes::TRANSACTIONAL,
                'OriginationNumber' => $messageSender,

            ] : [
                'Body' => preg_replace("/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags((string) $instance->getContent())))),
                'MessageType' => $this->messageType ?? PinpointMessageTypes::TRANSACTIONAL,
                'SenderId' => $messageSender,
            ];
            $params = [
                'ApplicationId' => $this->appId,
                'MessageRequest' => [
                    'Addresses' => array_values(
                        array_map(function ($address) {
                            return [$address, ['ChannelType' => $this->channel]];
                        }, array_filter([$instance->getReceiver()->__toString()]), static function ($address) {
                            return !empty(trim($address));
                        })
                    ),
                    'MessageConfiguration' => ['SMSMessage' => $message],
                ],
            ];

            return PinpointMessage::fromAWSResult($this->client->sendMessages($params));
        } catch (PinpointException $e) {
            return PinpointMessage::exception($e);
        }
    }
}
