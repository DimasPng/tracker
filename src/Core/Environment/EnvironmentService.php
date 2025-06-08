<?php

namespace App\Core\Environment;

use App\Contract\EnvironmentServiceInterface;
use App\Core\Request;

class EnvironmentService implements EnvironmentServiceInterface
{
    public function isHttps(?Request $request = null): bool
    {
        $server = $request ? $request->getAllServer() : $_SERVER;

        return (
            (isset($server['HTTPS']) && $server['HTTPS'] !== 'off') ||
            (isset($server['HTTP_X_FORWARDED_PROTO']) && $server['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (isset($server['HTTP_X_FORWARDED_SSL']) && $server['HTTP_X_FORWARDED_SSL'] === 'on') ||
            (isset($server['SERVER_PORT']) && (int) $server['SERVER_PORT'] === 443)
        );
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;
    }

    public function getUserAgent(?Request $request = null): string
    {
        $server = $request ? $request->getAllServer() : $_SERVER;

        return $server['HTTP_USER_AGENT'] ?? 'Unknown';
    }

    public function getClientIp(?Request $request = null): string
    {
        $server = $request ? $request->getAllServer() : $_SERVER;

        $ipKeys = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
        ];

        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $server) && !empty($server[$key])) {
                $ips = explode(',', $server[$key]);

                foreach ($ips as $ip) {
                    $ip = trim($ip);

                    if (filter_var($ip, FILTER_VALIDATE_IP)) {
                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                            return $ip;
                        }
                    }
                }
            }
        }

        $remoteAddr = $server['REMOTE_ADDR'] ?? '127.0.0.1';

        if ($remoteAddr === '::1') {
            $remoteAddr = '127.0.0.1';
        }

        return $remoteAddr;
    }

    public function getServerVar(string $key, mixed $default = null, ?Request $request = null): mixed
    {
        $server = $request ? $request->getAllServer() : $_SERVER;

        return $server[$key] ?? $default;
    }

    public function getCookie(string $key, mixed $default = null, ?Request $request = null): mixed
    {
        if ($request) {
            return $request->cookie($key, $default);
        }

        return $_COOKIE[$key] ?? $default;
    }
}
