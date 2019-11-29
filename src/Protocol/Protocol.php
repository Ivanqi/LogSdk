<?php declare(strict_types=1);
namespace LogSdk\Protocol;
use LogSdk\Protocol\SwoftJsonProtocol;

class Protocol
{
    const SWOFT_JSON_PROTOCOL = 'SwoftJsonProtocol';
    private static $mode;
    private static $sign = '';
    private static $funcName;

    public static function _encryption(array $request, string $sign, $mode = self::SWOFT_JSON_PROTOCOL): string
    {
        self::$mode = $mode;
        self::$sign = $sign;
        self::$funcName = __FUNCTION__;
        return self::switchFunc($request);
    }

    public static function _ecrypt(string $result, $mode = self::SWOFT_JSON_PROTOCOL): array
    {
        self::$mode = $mode;
        self::$sign = '';
        self::$funcName = __FUNCTION__;
        return self::switchFunc($result);
    }

    private static function switchFunc($request)
    {
        switch (self::$mode) {
            case self::SWOFT_JSON_PROTOCOL:
                return self::swoftJsonProtocol($request, self::$sign);
            default:
                return self::swoftJsonProtocol($request, self::$sign);    
        }
    }

    public static function swoftJsonProtocol($request, string $sign)
    {
        if (method_exists(SwoftJsonProtocol::class, self::$funcName)) {
            $args = [];
            array_push($args, $request, $sign);
            $result =  call_user_func_array([SwoftJsonProtocol::class, self::$funcName], $args);
            return $result;
        }
        return NULL;
    }    
}