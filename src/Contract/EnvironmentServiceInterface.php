<?php

namespace App\Contract;

use App\Core\Request;

interface EnvironmentServiceInterface
{
    public function isHttps(?Request $request = null): bool;

    public function get(string $key, mixed $default = null): mixed;

    public function getUserAgent(?Request $request = null): string;

    public function getClientIp(?Request $request = null): string;

    public function getServerVar(string $key, mixed $default = null, ?Request $request = null): mixed;

    public function getCookie(string $key, mixed $default = null, ?Request $request = null): mixed;
}
