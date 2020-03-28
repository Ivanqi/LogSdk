#### LogSdk
- 使用方式
  - 原生使用方式
    ```
    use LogSdk\TcpClient;
    use LogSdk\Exception\TcpClientException;
    use LogSdk\Protocol\Protocol;
    try {
        $client = new TcpClient(self::SIGN, Protocol::SWOFT_PHP_PROTOCOL);
        if (!$client->connect($ip, (int) $port, true)) {
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
    ```
  - Swoole 使用方式
    ```
    use LogSdk\SwooleTcpClient;
    use LogSdk\Exception\TcpClientException;

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
    ```  

#### 数据交互格式
- JSON 序列化. 无压缩
- PHP 序列化。可配置，deflate 压缩算法