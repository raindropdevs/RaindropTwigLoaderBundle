<?php

namespace Raindrop\TwigLoaderBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * FilesystemTwigUpdateCommand
 */
class FilesystemTwigUpdateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('raindrop:twig:filesystem:update')
            ->setDefinition(array(
                new InputArgument('bundle', InputArgument::REQUIRED, 'The bundle where to store the twig'),
                new InputOption(
                    'dump-twigs', null, InputOption::VALUE_NONE,
                    'Should the twigs be dumped in the console'
                ),
                new InputOption(
                    'force', null, InputOption::VALUE_NONE,
                    'Should the update be done'
                )
            ))
            ->setDescription('Updates the twig files in the filesystem')
            ->setHelp(<<<EOF
The <info>%command.name%</info> update twig files present in the filesystem fetching data from templates
stored in the database. It can display them or update the new ones into the twig files.

<info>php %command.full_name% --dump-twigs AcmeBundle</info>
<info>php %command.full_name% --force AcmeBundle</info>
EOF
            )
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // check presence of force or dump-twigs
        if ($input->getOption('force') !== true && $input->getOption('dump-twigs') !== true) {
            $output->writeln('<info>You must choose one of --force or --dump-twigs</info>');

            return 1;
        }
        
        $writer = $this->getContainer()->get('raindrop_twig.filesystem.writer');
        
        // get bundle directory
        $foundBundle = $this->getApplication()->getKernel()->getBundle($input->getArgument('bundle'));
        $bundleTwigPath = $foundBundle->getPath().'/Resources/views';
        $output->writeln(sprintf('Generating twig files in "<info>%s</info>"', $foundBundle->getName()));
        
        // load any existing twig file from the database
        $output->writeln('Loading twig files');
        $loader = $this->getContainer()->get('raindrop_twig.loader.database');

        // find all templates stored in database
        $twigs = $loader->findAll();
        
        // show compiled list of messages
        if ($input->getOption('dump-twigs') === true) {
            foreach ($twigs as $twig) {
                $output->writeln(sprintf("\nDisplaying twig with name <info>%s</info>:\n", $twig->getName()));
                $output->writeln($twig->getTemplate());
            }
        }        
        
        // save the files
        if ($input->getOption('force') === true) {
            $output->writeln('Writing files');
            $writer->writeTwigs($twigs, array('path' => $bundleTwigPath));
        }        
    }    
}
