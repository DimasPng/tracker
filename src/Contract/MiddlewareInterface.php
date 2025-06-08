<?php

namespace App\Contract;

use App\Core\Request;
use App\Core\Response;

interface MiddlewareInterface
{
    public function handle(Request $request): ?Response;
}
