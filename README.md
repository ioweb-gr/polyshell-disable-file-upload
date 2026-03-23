# Ioweb_PolyshellDisableFileUpload

Temporary Magento 2 hardening module that mitigates PolyShell-style abuse until the store is upgraded and fully patched.

## Features

The module provides two independent protections, both configurable from Magento admin at:

`Stores > Configuration > General > PolyShell Protection`

### 1. Disable PolyShell Uploads

Hard-blocks file custom option uploads:

- REST and API-driven file custom option payloads are rejected.
- Standard Magento file custom option validation is rejected too.

Use this if the store does not rely on file custom options at all.

### 2. Allow Only Image Extensions

Implements a Mark Shust-style mitigation:

- rejects non-image filename extensions during image content validation
- restricts the uploader to `jpg`, `jpeg`, `gif`, and `png`

Use this if you want a narrower mitigation and still need image-only behavior.

## Default configuration

For safety, both protections default to `Yes`.

## Installation

Add the repository to your project and require the package:

```bash
composer config repositories.ioweb-polyshell-disable-file-upload vcs https://github.com/ioweb-gr/polyshell-disable-file-upload.git
composer require ioweb/polyshell-disable-file-upload
bin/magento module:enable Ioweb_PolyshellDisableFileUpload
bin/magento setup:upgrade
bin/magento cache:flush
```

## Notes

- This module is a temporary mitigation, not a replacement for upgrading Magento.
- Keep web server protections on `/media/custom_options/` in place even with this module installed.
- If your store genuinely uses file custom options, test carefully before enabling the hard block mode.
