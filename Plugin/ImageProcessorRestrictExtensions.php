<?php

declare(strict_types=1);

namespace Ioweb\PolyshellDisableFileUpload\Plugin;

use Ioweb\PolyshellDisableFileUpload\Model\Config;
use Magento\Framework\Api\ImageProcessor;
use Magento\Framework\Api\Uploader;

class ImageProcessorRestrictExtensions
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'gif', 'png'];

    /** @var Uploader */
    private $uploader;

    /** @var Config */
    private $config;

    public function __construct(Uploader $uploader, Config $config)
    {
        $this->uploader = $uploader;
        $this->config = $config;
    }

    public function beforeProcessImageContent(ImageProcessor $subject, $entityType, $imageContent): ?array
    {
        if ($this->config->isAllowOnlyImageExtensionsEnabled()) {
            $this->uploader->setAllowedExtensions(self::ALLOWED_EXTENSIONS);
        }

        return null;
    }
}
