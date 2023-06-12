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

use Drewlabs\Envoyer\Contracts\NotificationResult;
use Drewlabs\Envoyer\Drivers\Aws\SESAdapter;
use Drewlabs\Envoyer\Drivers\Aws\Utils\CredentialsFactory;
use Drewlabs\Envoyer\Mail;
use PHPUnit\Framework\TestCase;

class SESAdapterTest extends TestCase
{
    public function test_sendgrid_adapter_send_request()
    {
        $config = require __DIR__ . '/contents/config.php';

        // Build email
        $mail = Mail::new()
            ->from($config['email'], 'SERVICES')
            ->to('azandrewdevelopper@gmail.com')
            ->subject('BORDERAU DE VIREMENTS')
            ->attach(new SplFileInfo(__DIR__ . '/contents/bordereau.pdf'))
            ->content('<p>Voici joint le fichier du bordereau de virement</p>');

        // Create mail adapter
        $adapter = SESAdapter::new([
            'region' => $config['region'],
            'credentials' => CredentialsFactory::create($config['user'], $config['password'])
        ]);

        // Send mail request
        $result = $adapter->sendRequest($mail);

        $this->assertInstanceOf(NotificationResult::class, $result);
        $this->assertTrue(null !== $result->id());
        $this->assertTrue($result->isOk());
    }
}
