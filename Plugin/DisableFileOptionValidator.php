<?php

declare(strict_types=1);

namespace Ioweb\PolyshellDisableFileUpload\Plugin;

use Ioweb\PolyshellDisableFileUpload\Model\Config;
use Magento\Catalog\Model\Product\Option\Type\File\ValidatorFile;
use Magento\Framework\Exception\LocalizedException;

class DisableFileOptionValidator
{
    /** @var Config */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function beforeValidate(ValidatorFile $subject, $processingParams, $option): array
    {
        if ($this->config->isDisableUploadsEnabled()) {
            throw new LocalizedException(
                __('File custom option uploads are temporarily disabled for security reasons.')
            );
        }

        return [$processingParams, $option];
    }
}
