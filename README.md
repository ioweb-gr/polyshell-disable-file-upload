# Ioweb_PolyshellDisableFileUpload

Temporary Magento 2 hardening module that mitigates PolyShell-style abuse until the store is upgraded and fully patched.

## What it provides

The module includes three practical protections:

- A hard block for file custom option uploads.
- A narrower image-extension-only mitigation inspired by [Mark Shust's workaround](https://github.com/markshust/magento-polyshell-patch).
- A CLI command to scan and optionally clear files from `pub/media/custom_options`.

## Admin configuration

Configuration is available at:

`Stores > Configuration > Security > PolyShell Protection`

### Disable PolyShell Uploads

When enabled, the module hard-blocks file custom option uploads:

- REST and API-driven file custom option payloads are rejected.
- Standard Magento file custom option validation is rejected too.

Use this if the store does not rely on file custom options at all.

### Allow Only Image Extensions

When enabled, the module applies an image-only extension allowlist to the relevant Magento image upload path:

- rejects non-image filename extensions during image content validation
- restricts the uploader to `jpg`, `jpeg`, `gif`, and `png`

Use this if you want a narrower mitigation and still need image-only behavior.

## Default configuration

For safety, both protections default to `Yes`.

## CLI command

The module adds this command:

```bash
bin/magento ioweb:polyshell:custom-options:scan
```

Behavior:

- Dry-run by default: lists files under `pub/media/custom_options` that would be removed.
- Deletes only when `--force` is supplied.
- Ignores `.htaccess` and `.gitignore`.

Example:

```bash
bin/magento ioweb:polyshell:custom-options:scan --force
```

## Installation

Add the repository to your project and require the package:

```bash
composer config repositories.ioweb-polyshell-disable-file-upload vcs https://github.com/ioweb-gr/polyshell-disable-file-upload.git
composer require ioweb-gr/polyshell-disable-file-upload
bin/magento module:enable Ioweb_PolyshellDisableFileUpload
bin/magento setup:upgrade
bin/magento cache:flush
```

## Notes

- This module is a temporary mitigation, not a replacement for upgrading Magento.
- Keep web server protections on `/media/custom_options/` in place even with this module installed.
- If your store genuinely uses file custom options, test carefully before enabling the hard block mode.
