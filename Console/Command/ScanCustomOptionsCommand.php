<?php

declare(strict_types=1);

namespace Ioweb\PolyshellDisableFileUpload\Console\Command;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ScanCustomOptionsCommand extends Command
{
    private const NAME = 'ioweb:polyshell:custom-options:scan';
    private const OPTION_FORCE = 'force';
    private const RELATIVE_SCAN_PATH = 'custom_options';

    public function __construct(
        private readonly Filesystem $filesystem
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::NAME)
            ->setDescription('Detect and optionally clear files from pub/media/custom_options')
            ->addOption(
                self::OPTION_FORCE,
                null,
                InputOption::VALUE_NONE,
                'Delete detected files instead of running in dry mode'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);

        if (!$mediaDirectory->isExist(self::RELATIVE_SCAN_PATH)) {
            $io->success('No pub/media/custom_options directory exists. Nothing to do.');
            return Command::SUCCESS;
        }

        $files = $this->getCandidateFiles($mediaDirectory);
        if ($files === []) {
            $io->success('No files found to clear under pub/media/custom_options.');
            return Command::SUCCESS;
        }

        $absoluteBasePath = rtrim($mediaDirectory->getAbsolutePath(self::RELATIVE_SCAN_PATH), '/');
        $io->title('PolyShell custom_options scan');
        $io->writeln('Base path: ' . $absoluteBasePath);
        $io->newLine();
        $io->listing($files);

        if (!$input->getOption(self::OPTION_FORCE)) {
            $io->warning('Dry run only. Re-run with --force to delete the files listed above.');
            return Command::SUCCESS;
        }

        $deleted = 0;
        foreach ($files as $file) {
            try {
                $mediaDirectory->delete($file);
                $deleted++;
            } catch (\Throwable $exception) {
                $io->error(sprintf('Failed to delete %s: %s', $file, $exception->getMessage()));
                return Command::FAILURE;
            }
        }

        $io->success(sprintf('Deleted %d file(s) from pub/media/custom_options.', $deleted));
        return Command::SUCCESS;
    }

    /**
     * @return string[]
     */
    private function getCandidateFiles(ReadInterface $mediaDirectory): array
    {
        $allFiles = $mediaDirectory->readRecursively(self::RELATIVE_SCAN_PATH);

        $files = array_values(array_filter(
            $allFiles,
            static function (string $path): bool {
                if (str_ends_with($path, '/')) {
                    return false;
                }

                $basename = basename($path);
                return !in_array($basename, ['.htaccess', '.gitignore'], true);
            }
        ));

        sort($files);
        return $files;
    }
}
