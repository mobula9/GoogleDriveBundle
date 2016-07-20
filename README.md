[![Latest Stable Version](https://poser.pugx.org/lucascherifi/google-drive-bundle/v/stable)](https://packagist.org/packages/lucascherifi/google-drive-bundle) [![Build Status](https://travis-ci.org/lucascherifi/GoogleDriveBundle.svg?branch=master)](https://travis-ci.org/lucascherifi/GoogleDriveBundle) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/24691eed-0a24-4c4c-9fda-81d67cf337ce/mini.png)](https://insight.sensiolabs.com/projects/24691eed-0a24-4c4c-9fda-81d67cf337ce) [![License](https://poser.pugx.org/lucascherifi/google-drive-bundle/license)](https://packagist.org/packages/lucascherifi/google-drive-bundle)

Google Drive Bundle
===================

The purpose of this bundle is to improve the usage of Google Drive API within Symfony applications.

The main features of this bundle are:

- **Resources Finder**
    - Search a resource
    - List children of a resource
    - Get resource metadata
    - Download files

- **Resources Modifier**
    - Move a resource to another drive directory
    - Remove a resource (trash or not)
    - Rename a resource
    - Upload a resource to a drive directory
    - Create a drive directory

- **Webhook management**
    - Watch/unwatch resource
    - Handle notifications via tagged processor services (you can create yours)
    - Fully manage subcriptions (Symfony commands provided to auto-update subscriptions)

# Prerequisites

- Google Oauth Account and its p12 file (via Google Developer Console)
- Web endpoint available with signed HTTPS (not auto-signed, Google accepts to send notifications only via real signed HTTPS)

# Bundle limitations

Currently, this bundle is only work with *Doctrine ORM*.

I'd love someone to be interested to develop ODM version :)


Installation
------------

- [Installation documentation](https://github.com/lucascherifi/GoogleDriveBundle/blob/master/doc/install.md)

Usage examples
--------------

- [File download example](https://github.com/lucascherifi/GoogleDriveBundle/blob/master/doc/download.md)

Contributing
------------

- [Contributing documentation](https://github.com/lucascherifi/GoogleDriveBundle/blob/master/doc/contributing.md)

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    ./LICENSE
