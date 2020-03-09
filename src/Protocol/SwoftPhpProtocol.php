<?php declare(strict_types=1);
namespace LogSdk\Protocol;

class SwoftPhpProtocol
{
    const DEFAULT_CMD = 'receive';
    public static function _encryption(array $request, string $sign, bool $compoesr = false): string
    {
        $time = time();
        $request['sign'] = self::getSign($request, $time, $sign);
        $request['time'] = $time;
        if ($compoesr) {
            $request = gzcompress(serialize($request));
        }
        $req = [
            'cmd' => self::DEFAULT_CMD,
            'data' => $request,
            'ext' => []
        ];

        $req = serialize($req);
        return $req;
    }

    public static function _decrypt($request)
    {
        return unserialize($request, true);
    }

    private static function getSign(array $data, int $time, string $sign)
    {
        $str = $sign . '#' . $time;
        foreach ($data as $k => $v) {
            $v = is_array($v) ? serialize($v) : $v;
            $str .= '#' . $k . '|' . $v;
        }
        return md5($str);
    }
}