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

interface IdentityVerifier
{
    /**
     * verify email identiy that will be used for sending emails.
     *
     * @return \Aws\Result
     */
    public function verifyEmailIdentity(string $email);

    /**
     * Amazon SES can send email only from verified email addresses or domains.
     * By verifying an email address, you demonstrate that you’re the owner of
     * that address and want to allow Amazon SES to send email from that address.
     * This method allow creating a verified domain on one aws account.
     *
     * @return \Aws\Result
     */
    public function verifyDomainIdentity(string $domain);

    /**
     * Use this method to  retrieve a list of email addresses submitted
     * in the current AWS Region, regardless of verification status,.
     *
     * @return \Aws\Result
     */
    public function listEmailAddresses();
}
