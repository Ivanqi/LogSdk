<?php declare(strict_types=1);
require_once "../vendor/autoload.php";

use LogSdk\SwooleTcpClient;
use LogSdk\Exception\TcpClientException;


$data = [
    ['name' => 'ivan', 'age' => 26, 'height' => '62kg', 'high' => '167cm'],
    ['name' => 'siki', 'age' => 21, 'height' => '40kg', 'high' => '155cm'],
    ['name' => 'yuki', 'age' => 10, 'height' => '20kg', 'hight' => '132cm'],
    ['name' => 'ivan', 'age' => 26, 'height' => '62kg', 'high' => '167cm'],
    ['name' => 'siki', 'age' => 21, 'height' => '40kg', 'high' => '155cm'],
    ['name' => 'yuki', 'age' => 10, 'height' => '20kg', 'hight' => '132cm'],
    ['name' => 'ivan', 'age' => 26, 'height' => '62kg', 'high' => '167cm'],
    ['name' => 'siki', 'age' => 21, 'height' => '40kg', 'high' => '155cm'],
    ['name' => 'yuki', 'age' => 10, 'height' => '20kg', 'hight' => '132cm'],
    ['name' => 'ivan', 'age' => 26, 'height' => '62kg', 'high' => '167cm'],
    ['name' => 'siki', 'age' => 21, 'height' => '40kg', 'high' => '155cm'],
    ['name' => 'yuki', 'age' => 10, 'height' => '20kg', 'hight' => '132cm'],
    ['name' => 'ivan', 'age' => 26, 'height' => '62kg', 'high' => '167cm'],
    ['name' => 'siki', 'age' => 21, 'height' => '40kg', 'high' => '155cm'],
    ['name' => 'yuki', 'age' => 10, 'height' => '20kg', 'hight' => '132cm'],
    ['name' => 'ivan', 'age' => 26, 'height' => '62kg', 'high' => '167cm'],
    ['name' => 'siki', 'age' => 21, 'height' => '40kg', 'high' => '155cm'],
    ['name' => 'yuki', 'age' => 10, 'height' => '20kg', 'hight' => '132cm'],
];



class TestSocketSwoft
{
    const SIGN = '#ivan_is_handsome_body#';
    

    public static function start($ip, $port, $data) 
    {
        if (empty($ip) || empty($port)) {
            echo "ip or port is empty.";
            die;
        }
        try {
            $client = new SwooleTcpClient(self::SIGN);
            if (!$client->connect($ip, (int) $port)) {
                echo "连接失败";
                die;
            }

            $ret = $client->send($data);

            if (!$ret) {
                echo "发送数据失败";
                die;
            } else {
                $msg = $client->recv(1024);
            }

            print_r($msg);
        
        } catch(\TcpClientException $e) {
            print_r(['e', $e]);
        }
    }
}

$opt = getopt("i:p:");
if (empty($opt)) {
    echo "请按 `" . __FILE__ . " -i 127.0.0.1 -p 8080 ` 输入\n";
    die;
}
TestSocketSwoft::start($opt['i'], $opt['p'], $data);