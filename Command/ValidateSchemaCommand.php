<?php

/*
 * This file is part of the Doctrine Bundle
 *
 * The code was originally distributed inside the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 * (c) Doctrine Project, Benjamin Eberlei <kontakt@beberlei.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SCM\MssqlBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\ValidateSchemaCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to run Doctrine ValidateSchema() on the current mappings.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 * @author Neil Katin <symfony@askneil.com>
 */
class DoctrineValidateSchemaCommand extends ValidateSchemaCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('doctrine:schema:validate')
            ->addOption('em', null, InputOption::VALUE_OPTIONAL, 'The entity manager to use for this command');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        DoctrineCommandHelper::setApplicationEntityManager($this->getApplication(), $input->getOption('em'));

        return $this->execute_cmd($input, $output);
    }

    protected function execute_cmd(InputInterface $input, OutputInterface $output)
    {
        $emHelper = $this->getHelper('em');

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $emHelper->getEntityManager();

        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        $ignored = $this->getApplication()->getKernel()->getContainer()->getParameter('doctrine_extension.ignored_entities');

        $newMetadatas = array();
        foreach ($metadatas as $metadata) {
            if (!in_array($metadata->getName(), $ignored)) {
                array_push($newMetadatas, $metadata);
            }
        }

        if ( ! empty($newMetadatas)) {
            // Create SchemaTool
            $tool = new SchemaTool($em);

            return $this->executeSchemaCommand($input, $output, $tool, $newMetadatas);
        } else {
            $output->writeln('No Metadata Classes to process.');
            return 0;
        }
    }
}
