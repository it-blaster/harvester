<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ODM\MongoDB\DocumentManager;
use AppBundle\Document\Log;
use AppBundle\Document\User;
use AppBundle\Document\UserLog;

/**
 * @Route("/logs")
 */
class LogsController extends Controller
{
    /**
     * @Route("/add", name="log-add")
     */
    public function addAction(Request $request)
    {
        if (!$log_params=$request->get('log_params')) {
            throw $this->createNotFoundException('Log params not fonud');
        }

        if (!is_array($log_params)){
            throw $this->createNotFoundException('Bad log params');
        }

        $user_name = (isset ($log_params['user']) ? $log_params['user'] : null);
        $uri =(isset($log_params['uri']) ? $log_params['uri'] : null);
        $page_title =(isset($log_params['page_title']) ? $log_params['page_title'] : null);

        if (!$user_name || !$uri){
            throw $this->createNotFoundException('Bad log params');
        }

        $this->addUserLog($user_name, $uri, $page_title);

        // replace this example code with whatever you need
        return new Response('success');
    }

    /**
     * @Route("/event.js", name="event-file-js")
     */
    public function eventFileJsAction(Request $request)
    {
        return $this->render('AppBundle:logs:event_file.js.twig', []);
    }

    /**
     * @Route("/test", name="log-test-page")
     */
    public function testAction(Request $request)
    {
        return $this->render('AppBundle:logs:test_page.html.twig', []);
    }

    private function addUserLog($user_name, $uri, $page_title = '')
    {
        /** @var DocumentManager $dm */
        $dm = $this->container->get('doctrine_mongodb')->getManager();
        $rep = $dm->getRepository('AppBundle:UserLog');

        /** @var UserLog $user */
        $user = $rep->findOneBy(['user_name'=>$user_name]);

        if (!$user) {
            $user = new UserLog();
            $user->setUserName($user_name);
        }

        //добавляем логи
        $log = new Log();
        $log
            ->setUri($uri)
            ->setTitle($page_title);

        $user->addLog($log);

        $dm->persist($user);
        $dm->flush();
    }

    /**
     *
     * @Route("/users", name="log-users")
     */
    public function userListAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user || !$user->hasRole('ROLE_ADMIN')) {
            throw $this->createNotFoundException('Access denied');
        }

        $dm = $this->container->get('doctrine_mongodb')->getManager();
        $rep = $dm->getRepository('AppBundle:UserLog');
        $users = $dm
            ->createQueryBuilder('AppBundle:UserLog')
            ->sort('updated_at', 'DESC')
            ->getQuery()
            ->execute();

        return $this->render('AppBundle:logs:users.html.twig', [
            'users' => $users
        ]);
    }

    /**
     *
     * @Route("/users/{user_name}", name="user-logs")
     */
    public function userLogsAction(Request $request, $user_name)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user || !$user->hasRole('ROLE_ADMIN')) {
            throw $this->createNotFoundException('Access denied');
        }

        $dm = $this->container->get('doctrine_mongodb')->getManager();
        $rep = $dm->getRepository('AppBundle:UserLog');
        $user_log = $rep->findOneBy(['user_name'=>$user_name]);

        $logs_list = [];
        $i=0;
        $logs = $user_log->getLogs()->slice(-20,20);
        foreach ($logs as $log) {
            $logs_list[$i++] = $log;
        }
        krsort($logs_list);

        return $this->render('AppBundle:logs:user_logs.html.twig', [
            'user'  => $user_log,
            'logs'  => $logs_list
        ]);
    }
}
