<?php

namespace ro0NL\Link;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;

/**
 * @author Roland Franssen <franssen.roland@gmail.com>
 */
class Link implements Capable, CommandProvider, PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
    }

    public function getCapabilities()
    {
        return [
            CommandProvider::class => __CLASS__,
        ];
    }

    public function getCommands()
    {
        return [
            new Command\LinkCommand(),
        ];
    }
}
