<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 22.11.2014
 */

namespace Erliz\SilexCommonBundle\Command;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class DumpDataBaseCommand extends ApplicationAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('orm:fixtures:dump')
            ->setDefinition($this->createDefinition())
            ->setDescription('Dump all data from Data Base to fixtures file')
            ->setHelp(<<<EOF
The <info>%command.name%</info> load all data from Data Base to fixtures file
EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getProjectApplication();
        /** @var EntityManager $em */
        $em = $app['orm.em'];

        $entityClassNames = $em->getConfiguration()
                               ->getMetadataDriverImpl()
                               ->getAllClassNames();

        foreach ($entityClassNames as $entityClassName) {
            $entities = $em->getRepository($entityClassName)->findAll();
            $data = array();
            foreach ($entities as $entity) {
                $data[] = $entity->toArray();
            }

            $dividedClassName = explode('\\',$entityClassName);
            $className = array_pop($dividedClassName);
            $filePath = $input->getArgument('to') . DIRECTORY_SEPARATOR . $className . '.yml';
            file_put_contents($filePath, Yaml::dump($data));

            $output->writeln(sprintf('Dumped <info>"%s"</info> to <info>"%s"</info>', $entityClassName, $filePath));
        }

    }

    /**
     * @return array
     */
    private function createDefinition()
    {
        return array(
            new InputArgument('to', InputArgument::REQUIRED, 'directory or file where the fixtures should be dumped'),
        );
    }
} 