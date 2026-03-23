<?php

declare(strict_types=1);

namespace Ioweb\PolyshellDisableFileUpload\Plugin;

use Ioweb\PolyshellDisableFileUpload\Model\Config;
use Magento\Framework\Api\Data\ImageContentInterface;
use Magento\Framework\Api\ImageContentValidator;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Filesystem\Io\File as IoFile;
use Magento\Framework\Phrase;

class ImageContentValidatorExtension
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'gif', 'png'];

    /** @var IoFile */
    private $ioFile;

    /** @var Config */
    private $config;

    public function __construct(IoFile $ioFile, Config $config)
    {
        $this->ioFile = $ioFile;
        $this->config = $config;
    }

    public function afterIsValid(
        ImageContentValidator $subject,
        bool $result,
        ImageContentInterface $imageContent
    ): bool {
        if (!$this->config->isAllowOnlyImageExtensionsEnabled()) {
            return $result;
        }

        $fileName = $imageContent->getName();
        $pathInfo = $this->ioFile->getPathInfo($fileName);
        $extension = strtolower($pathInfo['extension'] ?? '');

        if ($extension && !in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            throw new InputException(
                new Phrase('The image file extension "%1" is not allowed.', [$extension])
            );
        }

        return $result;
    }
}
