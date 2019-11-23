<?php declare(strict_types=1);
namespace LogSdk;
use LogSdk\Interfaces\ClientInterface;
use LogSdk\Exception\TcpClientException;
use LogSdk\Protocol\Protocol;

class TcpClient implements ClientInterface 
{
    const EOF = "\r\n\r\n";
    public  $keepAlive = true;

    // 1024 * 8 缓存区 = 8kb
    public $readBuffer = 8192;
    public $writeBuffer = 8192;
    private $ip;
    private $port;
    private $clientStream;
    private static $timeout = 2;
    private $sign;
    private $tcpPrefix = 'tcp://';
    private $eof = '';

    public function __construct($sign = '', $eof = self::EOF)
    {
        $this->sign = $sign;
        $this->eof = $eof;
    }


    public function connect(string $ip, int $port, int $mode = 0): TcpClient
    {
        if (empty($ip)) {
            throw new TcpClientException(TcpClientException::IP_IS_EMPTY);
        }

        if (empty($port)) {
            throw new TcpClientException(TcpClientException::PORT_IS_EMPTY);
        }

        $this->ip = $ip;
        $this->port = $port;

        $this->clientStream = $this->createClientStream();
        return $this;
    }
    
    private function tcpUrl(): string
    {
        $url = sprintf("%s%s:%s", $this->tcpPrefix, $this->ip, $this->port);
        return $url;
    }

    private function createClientStream(): resource
    {
        // 创建上下文
        $context = @stream_context_create();
        $url = $this->tcpUrl();
        $errno = 0;
        $errmsg = '';
        // 用 url 和上下文创建服务器字节流
        $clientStream = @stream_socket_client($url, $errno, $errmsg, 3600, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $context);
        if ($clientStream == false) {
            throw new TcpClientException($errmsg, $errno);
        }

        // stream_set_blocking 将一个数据流设置为堵塞或者非堵塞状态
        @stream_set_blocking($clientStream, false);
        // stream_set_read_buffer 设置流读文件缓冲区
        @stream_set_read_buffer($clientStream, $this->readBuffer);
        // stream_set_write_buffer 设置流写文件缓冲区
        @stream_set_write_buffer($clientStream, $this->writeBuffer);

        // 用客户端字节流创建socket
        $socket = @socket_import_stream($clientStream);

        // 设置配置项作用范围，第二个参数，对socket有效
        @socket_set_option($socket, SOL_SOCKET, SO_KEEPALIVE, (int) $this->keepAlive);

        // 设置配置项作用范围，第二个参数，针对TCP有效
        @socket_set_option($socket, SOL_TCP, TCP_NODELAY, (int) $this->noDelay);

        return $clientStream;
    }

    public function send(array $request): bool
    {
        if (empty($request)) {
            throw new TcpClientException(TcpClientException::DATA_IS_EMPTY);
        }
        $request = Protocol::_deProtocol($request);
        // stream_socket_sendto 向Socket发送数据，不管其连接与否
        @stream_socket_sendto($this->clientStream, $request . $this->eof);
        return true;
    }

    public function recv(int $length): string
    {
        $result = '';
        $info = stream_get_meta_data($this->clientStream);
        $hardTimeLimit = time() + self::$timeout + 2;

        while (!$info['timed_out'] && !feof($this->clientStream)) {
            $tmp = stream_socket_recvfrom($this->clientStream, $length);

            if ($pos = strpos($tmp, $this->eof)) {
                $result .= substr($tmp, 0, $pos);
                break;
            } else {
                $result .= $tmp;
                if (mb_strlen($request, 'ASCII') == $length) {
                    break;
                }
            }
            $info = stream_get_meta_data($this->clientStream);
            if (time() >= $hardTimeLimit) {
                throw new TcpClientException(TcpClientException::RECV_TIMEOUT);
            }
        }

        if ($info['timed_out']) {
            throw new TcpClientException(TcpClientException::RECV_TIMEOUT);
        }
        return $result;
    }
}