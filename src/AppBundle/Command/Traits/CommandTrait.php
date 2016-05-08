<?php

namespace AppBundle\Command\Traits;

trait CommandTrait
{
    protected $output;

    /**
     * Вывод ссобщения в консоль
     *
     * @param $message
     */
    protected function log($message, $color = '')
    {
        $colors = [
            'green'     => 'info',
            'yellow'    => 'comment',
            'red'       => 'error'
        ];

        if ($color && isset($colors[$color])) {
            $message = '<'.$colors[$color].'>'.$message.'</'.$colors[$color].'>';
        }
        $this->output->writeln($message);
    }

    /**
     * Выводит последний SQL-запрос
     */
    protected function dumpSQL()
    {
        $sql = \Propel::getConnection('propel')->getLastExecutedQuery();
        dump($sql);
    }
}