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

...

Step 4: Create a custom processors to handle notifications
----------------------------------------------------------

...

Step 5: Setup routes to handle notifications for Google Drive API
-----------------------------------------------------------------

...

Step 6: Setup the download directory
------------------------------------

...

Step 7: Setup the CRON task to auto-update resource subscriptions
-----------------------------------------------------------------

...
