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

use Aws\Ses\SesClient;
use Drewlabs\Envoyer\Contracts\AttachedAddressesAware;
use Drewlabs\Envoyer\Contracts\ClientInterface;
use Drewlabs\Envoyer\Contracts\NotificationInterface;
use Drewlabs\Envoyer\Contracts\NotificationResult;
use Drewlabs\Envoyer\Contracts\SubjectAware;
use Drewlabs\Envoyer\Drivers\Aws\Contracts\IdentityVerifier;

class SESAdapter implements ClientInterface, IdentityVerifier
{
    /**
     * @var SesClient
     */
    private $client;

    public function __construct(SesClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create new instance from parameters.
     *
     * @return static
     */
    public static function new(array $params)
    {
        return new static(new SesClient(array_merge(['version' => '2010-12-01'], $params ?? [])));
    }

    public function sendRequest(NotificationInterface $instance): NotificationResult
    {
        try {
            $encoding = 'UTF-8';
            $rawContent = preg_replace("/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags((string) ($content = $instance->getContent())))));
            $mail = [
                'Destination' => [
                    'ToAddresses' => [$instance->getReceiver()->__toString()],
                    'BccAddresses' => $instance instanceof AttachedAddressesAware ? array_values(array_filter($instance->getAttachedAddresses(), static function ($address) {
                        return !empty(trim((string) $address));
                    })) : [],
                ],
                'ReplyToAddresses' => [$instance->getSender()->__toString()],
                'Source' => $instance->getSender()->__toString(),
                'Message' => [
                    'Body' => [
                        'Html' => [
                            'Charset' => $encoding,
                            'Data' => $content,
                        ],
                        'Text' => [
                            'Charset' => $encoding,
                            'Data' => $rawContent,
                        ],
                    ],
                    'Subject' => [
                        'Charset' => $encoding,
                        'Data' => $instance instanceof SubjectAware ? $instance->getSubject() : 'No Subject'
                    ]
                ],
                'ConfigurationSetName' => null,
            ];

            return SESMessage::fromAWSResult($this->client->sendEmail($mail));
        } catch (\Exception $e) {
            return SESMessage::exception($e);
        }
    }

    // #region identify verifier

    public function verifyEmailIdentity($email)
    {
        try {
            return $this->client->verifyEmailIdentity([
                'EmailAddress' => $email,
            ]);
        } catch (\Aws\Exception\AwsException $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    public function verifyDomainIdentity($domain)
    {
        try {
            return $this->client->verifyDomainIdentity([
                'Domain' => $domain,
            ]);
        } catch (\Aws\Exception\AwsException $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    public function listEmailAddresses()
    {
        try {
            return $this->client->listIdentities([
                'IdentityType' => 'Domain',
            ]);
        } catch (\Aws\Exception\AwsException $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    public function deleteIdentity($identity)
    {
        try {
            return $this->client->deleteIdentity([
                'Identity' => $identity,
            ]);
        } catch (\Aws\Exception\AwsException $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }
    // endregion identify verifier
}
