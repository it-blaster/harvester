<?php

namespace AppBundle\Command;

use AppBundle\Command\Traits\CommandTrait;
use AppBundle\Document\Log;
use AppBundle\Document\UserLog;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;


class LogListCommand extends ContainerAwareCommand
{
    use CommandTrait;

    protected function configure()
    {
        $this
            ->setName('log:list')
            ->setDescription('Logs users');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var OutputInterface $output */
        $this->output = $output;

        /** @var DocumentManager $dm */
        $dm = $this->getContainer()->get('doctrine_mongodb')->getManager();
        $rep = $dm->getRepository('AppBundle:UserLog');

        //$log_list = $rep->findAll();
        $log_list = $dm
            ->createQueryBuilder('AppBundle:UserLog')
            //->field('user_name')->equals('vdanilov')
            ->limit(10)
            ->sort('user_name', 'ASC')
            ->getQuery()
            ->execute();

        foreach ($log_list as $user) {
            $user_last_logs = '';
            $last_logs = $user->getLogs()->slice(-5,5);

            foreach ($last_logs as $log) {
                /** @var Log $log */
                $user_last_logs.=$log->getTitle().' '.$log->getUri().' '.$log->getDateTimeFormat().'
';
            }

            $logs[] = [
                $user->getUserName(),
                $user->getCreatedAtFormat(),
                $user->getUpdatedAtFormat(),
                $user->getLogs()->count(),
                $user_last_logs
            ];
        }

        $table = new Table($output);
        $table
            ->setHeaders(['User name', 'Created', 'Updated', 'Log count', 'Last logs'])
            ->addRows($logs)
            ->render();

    }

}