
File download example
=====================

In this short example, we download every file in a Google Drive directory.

```php
$driveFinder = $this->get('kasifi_gdrive.finder');
$files = $driveFinder->getFolderChildren('google-id-of-a-drive-directory');
foreach ($files as $file) {
    $driveFinder->fileDownload($file);
}
```

You'll find downloaded files in `%kernel.root_dir%/../data/documents`.

If you want to download a file elsewhere, you can call the `fileDownload` method as :

```php
$driveFinder->fileDownload($file, '/another/path/');
```