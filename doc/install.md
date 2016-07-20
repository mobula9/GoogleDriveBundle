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

Step 2 : Add your generated p12 file and create "downloads" directory
---------------------------------------------------------------------

Download key.p12 file from the Google APIs Console at https://code.google.com/apis/console/b/0/ "Create another client ID..." -> "Service Account" -> Download the certificate as "key.p12" and move it to:

    %kernel.root_dir%/config/google.p12

Create the download directory to receive downloaded files: 

    %kernel.root_dir%/../data/documents

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

Step 4: Add some parameters to the parameters.yml file
------------------------------------------------------

```yaml
// app/config/parameters.yml
...
kasifi_gdrive.google_drive_client_email: your@app.email # @see it in https://code.google.com/apis/console/b/0/
kasifi_gdrive.google_auth_sub: ~  # if using google apps for enterprises set your email @see https://github.com/google/google-api-php-client/blob/v1.0.1-beta/src/Google/Auth/AssertionCredentials.php#L61 
gdrive_oauth_https_callback_prefix: https://yousite.com # will be use by google drive to send you webhook notifications
kasifi_gdrive.notification_config:
    - name: "My first watched drive directory"
      type: 0 # specify a value of your convenience, useful to help you to handle the notification at the end 
      id: "google-id-of-first-watched-directory"
    - name: "My second watched drive directory"
      type: 1 # idem
      id: "google-id-of-second-watched-directory"
    - name: "My other drive directory"
      type: 2 # idem
      id: "google-id-of-other-watched-drive-directory"
    ...
...
```

Step 5: Enable the Bundle
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

Step 6: Create a custom processors to handle notifications
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

Step 7: Setup routes to handle notifications for Google Drive API
-----------------------------------------------------------------
```yaml
// app/routing.yml
kasifi_gdrive:
    resource: "@Kasifi/GoogleDriveBundle/Controller/NotificationsController.php"
    type:     annotation
```

Step 8: Setup the download directory
------------------------------------
```bash
cd [PROJECT_ROOT]/data/documents
```

Step 9: Setup the CRON task to auto-update resource subscriptions
-----------------------------------------------------------------

```bash
crontab -e
```

```bash
*/4 0-3,8-0 * * * cd [PROJECT_ROOT] && /usr/bin/php app/console gdrive:notifications:update-subscription -e prod 2>&1 >> /dev/null
```
