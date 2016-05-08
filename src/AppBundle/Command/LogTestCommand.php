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

        $username = 'isakova';

        /** @var DocumentManager $dm */
        $dm = $this->getContainer()->get('doctrine_mongodb')->getManager();
        $rep = $dm->getRepository('AppBundle:UserLog');

        /** @var UserLog $user */
        $user = $rep->findOneBy(['user_name'=>$username]);

        if (!$user) {
            $user = new UserLog();
            $user->setUserName($username);
        }

        //добавляем логи
        $log = new Log();
        $log
            ->setUri('http://artsofte.ru/news')
            ->setTitle('Artsofte - Новости');

        $user->addLog($log);

        $dm->persist($user);
        $dm->flush();

        $logs = [];

        //$log_list = $rep->findAll();
        $log_list = $dm
            ->createQueryBuilder('AppBundle:UserLog')
            //->field('user_name')->equals('vdanilov')
            ->limit(10)
            ->sort('user_name', 'ASC')
            ->getQuery()
            ->execute();

        foreach ($log_list as $user) {
            $logs[] = [
                $user->getUserName(),
                $user->getCreatedAtFormat(),
                $user->getUpdatedAtFormat(),
                count($user->getLogs())
            ];
        }

        $table = new Table($output);
        $table
            ->setHeaders(['User name', 'Created', 'Updated', 'Log count'])
            ->addRows($logs)
            ->render();

    }

}