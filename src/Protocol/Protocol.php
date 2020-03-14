<?php declare(strict_types=1);
namespace LogSdk\Protocol;
use LogSdk\Protocol\SwoftJsonProtocol;

class Protocol
{
    const SWOFT_JSON_PROTOCOL = 'SwoftJsonProtocol';
    const SWOFT_PHP_PROTOCOL = 'SwoftPhpProtocol';

    private static $mode;
    private static $sign = '';
    private static $funcName;
    private static $compress = false;

    public static function _encryption(array $data): string
    {
        $request = self::arrayGet($data, 'request', []);

        self::$compress = self::arrayGet($data, 'compress', false);
        self::$mode = self::arrayGet($data, 'mode', self::SWOFT_JSON_PROTOCOL);
        self::$sign = self::arrayGet($data, 'sign', '');
        self::$funcName = __FUNCTION__;
        $ret = self::switchFunc($request);
        return is_null($ret) ? [] : $ret;
    }

    public static function _decrypt(string $result, $mode = self::SWOFT_JSON_PROTOCOL): array
    {
        self::$mode = $mode;
        self::$sign = '';
        self::$funcName = __FUNCTION__;
        $ret =  self::switchFunc($result);
        return is_null($ret) ? [] : $ret;
    }

    private static function switchFunc($request)
    {
        switch (self::$mode) {
            case self::SWOFT_JSON_PROTOCOL:
                return self::swoftJsonProtocol($request, self::$sign, self::$compress);
            case self::SWOFT_PHP_PROTOCOL:
                return self::swoftPhpProtocol($request, self::$sign, self::$compress);
            default:
                return self::swoftJsonProtocol($request, self::$sign, self::$compress);    
        }
    }

    public static function swoftJsonProtocol($request, string $sign, bool $compress)
    {
        if (method_exists(SwoftJsonProtocol::class, self::$funcName)) {
            $args = [];
            array_push($args, $request, $sign);
            $result =  call_user_func_array([SwoftJsonProtocol::class, self::$funcName], $args);
            return $result;
        }
        return NULL;
    }
    
    public static function swoftPhpProtocol($request, string $sign, bool $compress)
    {
        if (method_exists(SwoftPhpProtocol::class, self::$funcName)) {
            $args = [];
            array_push($args, $request, $sign, $compress);
            $result =  call_user_func_array([SwoftPhpProtocol::class, self::$funcName], $args);
            return $result;
        }
        return NULL;
    }

    private function arrayGet($array, $key, $default = null)
    {
        $key = explode('.', $key);
        $data = $array;
        foreach ($key as $k) {
            if (is_array($data) && array_key_exists($k, $data)) {
                $data = $data[$k];
            } else {
                return $default;
            }
        }
        return $data;
    }
}