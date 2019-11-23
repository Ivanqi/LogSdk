<?php declare(strict_types=1);
namespace LogSdk\Interfaces;

interface ClientInterface {
    public function send(array $request);
    public function recv(int $length);
    public function connect(string $url, int $port, int $mode = 0);
}