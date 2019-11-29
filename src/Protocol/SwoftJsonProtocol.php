<?php declare(strict_types=1);
namespace LogSdk\Protocol;

class SwoftJsonProtocol
{
    const DEFAULT_CMD = 'receive';
    public static function _encryption(array $request, string $sign): string
    {
        $time = time();
        $request['sign'] = self::getSign($request, $time, $sign);
        $request['time'] = $time;
        $req = [
            'cmd' => self::DEFAULT_CMD,
            'data' => $request,
            'ext' => []
        ];

        $req = json_encode($req);
        return $req;
    }

    public static function _ecrypt($request)
    {
        return json_decode($request, true);
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