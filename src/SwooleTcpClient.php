<?php declare(strict_types=1);
namespace LogSdk;
use LogSdk\Interfaces\ClientInterface;
use LogSdk\Exception\TcpClientException;
use LogSdk\Protocol\Protocol;

class SwooleTcpClient implements ClientInterface
{
    private $client;
    const EOF = "\r\n\r\n";
    public function __construct($sign = '', $eof = self::EOF)
    {
        $this->sign = $sign;
        $this->eof = $eof;

        $this->client = new \swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_SYNC);
    }

    
    public function connect(string $ip, int $port, int $mode = 0): bool
    {
        if (!$this->client->connect($ip, $port, 2)) {
            return false;
        }
        return true;
    }

    public function send(array $request): bool
    {
        if (empty($request)) {
            throw new TcpClientException(TcpClientException::DATA_IS_EMPTY);
        }
        $request = Protocol::_encryption($request, $this->sign);
        // stream_socket_sendto 向Socket发送数据，不管其连接与否
        $request = $request . $this->eof;
        if (empty($request)) {
            throw new TcpClientException(TcpClientException::DATA_IS_EMPTY);
        }
        return $this->client->send($request) ? true : false;
    }

    public function recv(int $length): array
    {
        $result = '';
        while(true) {
            $result = $this->client->recv($length);
            if (empty($result) or substr($result, -1, 1) == "\n") {
                break;
            }
        }
        $result = Protocol::_ecrypt($result, $this->sign);
        if (empty($result)) {
            throw new TcpClientException(TcpClientException::DATA_IS_EMPTY);
        }
        return $result;
    }
}