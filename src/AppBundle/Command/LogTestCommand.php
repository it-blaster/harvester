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
            ->setDescription('Testing logs');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var OutputInterface $output */
        $this->output = $output;

        $username = 'vdanilov';

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
            ->setUri('http://artsofte.ru/team')
            ->setTitle('Artsofte - Команда');

        $user->addLog($log);

        $dm->persist($user);
        $dm->flush();
    }

}