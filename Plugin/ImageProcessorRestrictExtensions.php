<?php

declare(strict_types=1);

namespace Ioweb\PolyshellDisableFileUpload\Plugin;

use Ioweb\PolyshellDisableFileUpload\Model\Config;
use Magento\Framework\Api\ImageProcessor;
use Magento\Framework\Api\Uploader;

class ImageProcessorRestrictExtensions
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'gif', 'png'];

    public function __construct(
        private readonly Uploader $uploader,
        private readonly Config $config
    ) {
    }

    public function beforeProcessImageContent(ImageProcessor $subject, $entityType, $imageContent): ?array
    {
        if ($this->config->isAllowOnlyImageExtensionsEnabled()) {
            $this->uploader->setAllowedExtensions(self::ALLOWED_EXTENSIONS);
        }

        return null;
    }
}
