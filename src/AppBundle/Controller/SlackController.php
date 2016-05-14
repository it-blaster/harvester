<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/slack")
 */
class SlackController extends Controller
{
    /**
     *
     * @Route("/test", name="slack-send-test")
     */
    public function testAction(Request $request)
    {
        $url = "http://harvester.yakimov.trade.dev/app_dev.php/slack/send";
        $query = [
            'slack_params[token]' => 'VHrUAa1YIvPVdPUT5c5VMzS3',
            'slack_params[chanel]' => 'hyundai_minusinsk',
            'slack_params[text]'  => 'test_mes'
        ];

        $curl = curl_init(); //инициализация сеанса
        curl_setopt($curl, CURLOPT_URL, $url); //урл сайта к которому обращаемся
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept-Charset: windows-1251,utf-8,q=0.7,*;q=0.7'));
        curl_setopt($curl, CURLOPT_POST, 1); //передача данных методом POST
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //теперь curl вернет нам ответ, а не выведет
        curl_setopt($curl, CURLOPT_POSTFIELDS, $query); //тут переменные которые будут переданы методом POST
        curl_setopt($curl, CURLOPT_USERAGENT, 'MSIE 5'); //эта строчка как-бы говорит: "я не скрипт, я IE5" :)
//            curl_setopt($curl, CURLOPT_COOKIE, $order_str);
        curl_setopt($curl, CURLOPT_REFERER, $url); //а вдруг там проверяют наличие рефера

        $res = curl_exec($curl);
        dump($res); die();

        $info = curl_getinfo($curl);
        if ($info['http_code']) {
            echo '
OK
';
        } else {
            echo "
BAD
";
        }

        curl_close($curl);

        return new Response('');
    }

    /**
     *
     * @Route("/send", name="slack-send")
     */
    public function sendAction(Request $request)
    {
        $params = $request->get('slack_params');
        $token = $params['token'];
        $chanel = $params['chanel'];
        $text = $params['text'];
        $command = "curl -X POST --data-urlencode 'payload={\"channel\": \"#".$chanel."\", \"username\": \"Bot\", \"text\": \" ".$text." \", \"icon_url\": \"http://careers.jobvite.com/curriculumassociates/icon-bus.png\"}' https://hooks.slack.com/services/T07NBV0QG/B0MQAKJN5/".$token;
        echo `$command`;
        return new Response('');
    }


}
