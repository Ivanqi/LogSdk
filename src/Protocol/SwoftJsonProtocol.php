<?php declare(strict_types=1);
namespace LogSdk\Protocol;

class SwoftJsonProtocol
{
    const DEFAULT_CMD = 'receive';
    public function _deProtocol(array $request, string $sign): string
    {
        $time = time();
        $data['sign'] = static::getSign($request, $time, $sign);
        $data['time'] = $time;

        $req = [
            'cmd' => self::DEFAULT_CMD,
            'data' => $data,
            'ext' => []
        ];

        $req = json_encode($req);
        return $req;
    }

    private static function getSign(array $data, int $time, string $sign)
    {
        $str = $sign . '#' . $time;
        foreach ($data as $k => $v) {
            $v = is_array($v) ? json_encode($v) : $v;
            $str .= '#' . $k . '|' . $v;
        }
        return md5($str);
    }
}