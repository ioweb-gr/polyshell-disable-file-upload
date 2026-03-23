<?php

declare(strict_types=1);

namespace Ioweb\PolyshellDisableFileUpload\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private const XML_PATH_DISABLE_UPLOADS = 'ioweb_polyshell/general/disable_uploads';
    private const XML_PATH_ALLOW_ONLY_IMAGE_EXTENSIONS = 'ioweb_polyshell/general/allow_only_image_extensions';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function isDisableUploadsEnabled(?string $storeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_DISABLE_UPLOADS,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function isAllowOnlyImageExtensionsEnabled(?string $storeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ALLOW_ONLY_IMAGE_EXTENSIONS,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }
}
