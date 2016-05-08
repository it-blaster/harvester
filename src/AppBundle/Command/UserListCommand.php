<?php

namespace AppBundle\Command;

use AppBundle\Command\Traits\CommandTrait;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;


class UserListCommand extends ContainerAwareCommand
{
    use CommandTrait;

    protected function configure()
    {
        $this
            ->setName('user:list')
            ->setDescription('command for testing');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var OutputInterface $output */
        $this->output = $output;

        $users = [];

        $user_list = $this->getContainer()->get('doctrine_mongodb')
        ->getRepository('AppBundle:User')
        ->findAll();

        foreach ($user_list as $user) {
            $users[] = [
                $user->getUserName(),
                implode(', ',$user->getRoles())
            ];
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Login', 'Credentials'])
            ->addRows($users)
            ->render();
    }

}