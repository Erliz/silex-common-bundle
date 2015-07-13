<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 21.11.2014
 */

namespace Erliz\SilexCommonBundle\Tests\Command;


use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Erliz\SilexCommonBundle\Command\ApplicationAwareCommand;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FixturesLoadCommand extends ApplicationAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('orm:fixtures:load')
            ->setDefinition($this->createDefinition())
            ->setDescription('Load all fixtures from DataLoader files in directory')
            ->setHelp(<<<EOF
The <info>%command.name%</info> load all fixtures from DataLoader files in directory
EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getProjectApplication();
        $em = $app['orm.em'];

        $dirOrFile = $input->getArgument('fixtures');
        if ($dirOrFile) {
            $paths = is_array($dirOrFile) ? $dirOrFile : array($dirOrFile);
        } else {
            $paths = array();
            foreach ($this->getApplication()->getKernel()->getBundles() as $bundle) {
                $paths[] = $bundle->getPath().'/DataFixtures/ORM';
            }
        }

        $loader = new Loader();
        foreach ($paths as $path) {
            if (is_dir($path)) {
                $loader->loadFromDirectory($path);
            }
        }
        $fixtures = $loader->getFixtures();
        if (!$fixtures) {
            throw new InvalidArgumentException(
                sprintf('Could not find any fixtures to load in: %s', "\n\n- ".implode("\n- ", $paths))
            );
        }
        $purger = new ORMPurger($em);
        $purger->setPurgeMode($input->getOption('purge-with-truncate') ? ORMPurger::PURGE_MODE_TRUNCATE : ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($em, $purger);
        $executor->setLogger(function($message) use ($output) {
                $output->writeln(sprintf('  <comment>></comment> <info>%s</info>', $message));
            });
        $executor->execute($fixtures, $input->getOption('append'));

    }

    /**
     * @return array
     */
    private function createDefinition()
    {
        return array(
            new InputArgument('fixtures', InputArgument::REQUIRED, 'directory where the fixtures classes should be loaded'),
            new InputOption('purge-with-truncate', null, InputOption::VALUE_NONE, 'Purge data by using a database-level TRUNCATE statement'),
            new InputOption('append', null, InputOption::VALUE_NONE, 'Append the data fixtures instead of deleting all data from the database first.')
        );
    }
} 