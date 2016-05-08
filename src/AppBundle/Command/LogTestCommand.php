<?php

namespace AppBundle\Command;

use AppBundle\Command\Traits\CommandTrait;
use AppBundle\Document\Log;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;


class LogTestCommand extends ContainerAwareCommand
{
    use CommandTrait;

    protected function configure()
    {
        $this
            ->setName('log:test')
            ->setDescription('command for testing');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var OutputInterface $output */
        $this->output = $output;

        $username = 'user_test';

        /** @var DocumentManager $dm */
        $dm = $this->getContainer()->get('doctrine_mongodb')->getManager();
        $rep = $dm->getRepository('AppBundle:Log');
        $log = $rep->findOneBy(['user_name'=>$username]);

        if (!$log) {
            $log = new Log();
            $log->setUserName($username);
        }

        $dm->persist($log);
        $dm->flush();

        $logs = [];

        //$log_list = $rep->findAll();
        $log_list = $dm
            ->createQueryBuilder('AppBundle:Log')
            //->field('user_name')->equals('vdanilov')
            ->limit(10)
            ->sort('user_name', 'ASC')
            ->getQuery()
            ->execute();

        foreach ($log_list as $log) {
            $logs[] = [
                $log->getUserName()
            ];
        }

        $table = new Table($output);
        $table
            ->setHeaders(['User name'])
            ->addRows($logs)
            ->render();
    }

}