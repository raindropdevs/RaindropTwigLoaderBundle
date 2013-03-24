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
class DatabaseTwigUpdateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('raindrop:twig:database:update')
            ->setDefinition(array(
                new InputArgument('bundle', InputArgument::REQUIRED, 'The bundle where the twig files are'),
                new InputOption(
                    'dump-twigs', null, InputOption::VALUE_NONE,
                    'Should the twigs be dumped in the console'
                ),
                new InputOption(
                    'force', null, InputOption::VALUE_NONE,
                    'Should the update be done'
                )
            ))
            ->setDescription('Updates the twig files in the database')
            ->setHelp(<<<EOF
The <info>%command.name%</info> update twig templates fetching data from templates
stored in the filesystem. It can display them or update the new ones into the database.

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
        
        $writer = $this->getContainer()->get('raindrop_twig.database.writer');
        
        // get bundle directory
        $foundBundle = $this->getApplication()->getKernel()->getBundle($input->getArgument('bundle'));
        $bundleTwigPath = $foundBundle->getPath().'/Resources/views';
        $output->writeln(sprintf('Generating twig files from "<info>%s</info>"', $foundBundle->getName()));
        
        $twigs = array();

        // load a existing twig file from the database
        $output->writeln('Loading twig files');
        $writer->extract($bundleTwigPath, &$twigs);
        
        // show compiled list of twig files
        if ($input->getOption('dump-twigs') === true) {
            foreach ($twigs as $twig) {
                $output->writeln(sprintf("\nDisplaying twig with name <info>%s</info>:\n", $twig->getRelativePathname()));
                $output->writeln(file_get_contents($twig->getPathname()));
            }
        }        
        
        // save the files to database
        if ($input->getOption('force') === true) {
            $output->writeln('Writing template on database');
            $writer->writeTwigs($twigs);
        }        
    }    
}
