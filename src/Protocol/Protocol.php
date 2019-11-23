<?php declare(strict_types=1);
namespace LogSdk\Protocol;

class Protocol
{
    const SWOFT_JSON_PROTOCOL = 'SwoftJsonProtocol';

    public static function _deProtocol(array $request, string $sign, $mode = self:: SWOFT_JSON_PROTOCOL): string
    {
        switch ($mode) {
            case self::SWOFT_JSON_PROTOCOL:
                return self::swoftJsonProtocol($request, $sign);
            default:
                return self::swoftJsonProtocol($request, $sign);
                
        }
    }

    public static function swoftJsonProtocol(array $request, string $sign): string
    {
        $func = '_deProtocol';
        if (method_exists(self::SWOFT_JSON_PROTOCOL, $func)) {
            return \call_user_func_array([self::SWOFT_JSON_PROTOCOL, $func], [$request, $sign]);
        }
        return '';
    }    
}