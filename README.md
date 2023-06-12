# AWS Drivers

The library provides `envoyer client` drivers/adapters for `AWS SES`, `AWS Pinpoint` services.
A `SESAdapter` is provided by the library as interface to `AWS Simple Email Service` API while, a `PinpointMessageAdapter` is provided as interface for `Short Message Service`.

## Usage

- AWS SES adapter

```php
use Drewlabs\Envoyer\Drivers\Aws\SESAdapter;
use Drewlabs\Envoyer\Drivers\Aws\Utils\CredentialsFactory;
use Drewlabs\Envoyer\Mail;

$config = require __DIR__ . '/config.php';

// Build email
$mail = Mail::new()
    ->from($config['email'], 'SERVICES')
    ->to('...')
    ->subject('...')
    ->content('...');

// Create mail adapter
$adapter = SESAdapter::new([
    'region' => $config['region'],
    // Creates a promise object for aws credentials
    'credentials' => CredentialsFactory::create($config['user'], $config['password'])
]);

// Send mail request
$result = $adapter->sendRequest($mail);
```
