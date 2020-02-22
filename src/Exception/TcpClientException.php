<?php declare(strict_types=1);
namespace LogSdk\Exception;

use Exception;
class TcpClientException extends \Exception
{
    const IP_IS_EMPTY = 'IP为空';
    const PORT_IS_EMPTY = '端口为空';
    const DATA_IS_EMPTY = '数据为空';
    const RECV_TIMEOUT = '读取数据超时';
    const CONNECT_FAILED = '连接失败';
    const DATA_SEND_FAILED = '数据发送失败';

    const ERROR_CODE = 0;


    public function __construct(string $mssages = "", $code = 0,  Exception $previous = null)
    {
        parent::__construct($mssages, $code, $previous);
    }
}