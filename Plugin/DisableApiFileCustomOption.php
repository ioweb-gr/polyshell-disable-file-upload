<?php

declare(strict_types=1);

namespace Ioweb\PolyshellDisableFileUpload\Plugin;

use Ioweb\PolyshellDisableFileUpload\Model\Config;
use Magento\Catalog\Api\Data\CustomOptionInterface;
use Magento\Catalog\Model\CustomOptions\CustomOption;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Phrase;

class DisableApiFileCustomOption
{
    public function __construct(
        private readonly Config $config
    ) {
    }

    public function aroundGetOptionValue(CustomOption $subject, callable $proceed)
    {
        if (
            $this->config->isDisableUploadsEnabled()
            && $subject->getData(CustomOptionInterface::OPTION_VALUE) === 'file'
        ) {
            throw new InputException(
                new Phrase('File custom option uploads are temporarily disabled for security reasons.')
            );
        }

        return $proceed();
    }
}
