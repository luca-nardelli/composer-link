<?php

namespace ro0NL\Link\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @author Roland Franssen <franssen.roland@gmail.com>
 */
class LinkCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('link')
            ->setDescription('Link dependencies to a local clone.')
            ->setDefinition([
                new InputArgument('path', InputArgument::REQUIRED, 'The path to link to'),
            ])
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');
        $io = $this->getIO();

        if (!is_dir($path.'/vendor')) {
            $io->writeError(sprintf('<error>The directory "%s" does not exist or the dependencies are not installed, did you forget to run `composer install` in your project?</error>', $path));

            return 1;
        }

        $packages = [];
        foreach (Finder::create()->in(getcwd())->notPath('/vendor/')->name('composer.json') as $package) {
            if (!($data = @json_decode(file_get_contents($package))) || !isset($data->name)) {
                continue;
            }
            $packages[$data->name] = dirname((string) $package);
        }

        $filesystem = new Filesystem();
        foreach (Finder::create()->in($path.'/vendor')->directories()->depth(1) as $dependency) {
            if (is_link($dependency)) {
                continue;
            }

            if (!isset($packages[$package = basename(dirname($dependency)).'/'.basename($dependency)])) {
                continue;
            }

            $dir = '\\' === DIRECTORY_SEPARATOR ? $packages[$package] : $filesystem->makePathRelative($packages[$package], dirname(realpath($dependency)));

            $filesystem->remove($dependency);
            $filesystem->symlink($dir, $dependency);

            $io->write(sprintf('<info>%s</info> has been linked to <info>%s</info>.', $package, $packages[$package]));
        }

        return 0;
    }
}
