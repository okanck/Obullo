<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Interop\Container\ContainerInterface as Container;

class GenerateBundleCommand extends Command
{
    public function __construct(Container $container)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('generate:bundle')
            ->setDescription('Generate bundles')
            ->addArgument(
                'action',
                InputArgument::OPTIONAL,
                'Do you want to generate a new bundle ?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
