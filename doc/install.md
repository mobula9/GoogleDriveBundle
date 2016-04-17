Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require lucascherifi/google-drive-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Kasifi\GoogleDriveBundle\GoogleDriveBundle(),
        );

        // ...
    }

    // ...
}
```

Step 3: Add a custom logger for Drive operations and notifications
------------------------------------------------------------------

```yaml
// app/config_dev.yml and app/config_prod.yml
...
monolog:
    handlers:
        ...
        drive:
            type: stream
            path: "%kernel.logs_dir%/drive.%kernel.environment%.log"
            level: info
            channels: [drive]
            formatter: monolog.formatter.session_request
    channels: [..., drive]
...
```

Step 4: Create a custom processors to handle notifications
----------------------------------------------------------

```php
<?php
namespace AppBundle\DriveProcessors;

use Kasifi\GoogleDriveBundle\Notification;
use Kasifi\GoogleDriveBundle\ProcessorInterface;
use Psr\Log\LoggerInterface;

class CustomProcessor implements ProcessorInterface {

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function processNotification(Notification $notification) {
        // manage the notification...
    }
}

```

Step 5: Setup routes to handle notifications for Google Drive API
-----------------------------------------------------------------
```yaml
// app/routing.yml
kasifi_gdrive:
    resource: "@Kasifi/GoogleDriveBundle/Controller/NotificationsController.php"
    type:     annotation
```

Step 6: Setup the download directory
------------------------------------
```bash
cd [PROJECT_ROOT]/data/documents
```

Step 7: Setup the CRON task to auto-update resource subscriptions
-----------------------------------------------------------------

```bash
crontab -e
```

```bash
*/4 0-3,8-0 * * * cd [PROJECT_ROOT] && /usr/bin/php app/console gdrive:notifications:update-subscription -e prod 2>&1 >> /dev/null
```
