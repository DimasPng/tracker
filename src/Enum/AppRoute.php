<?php

namespace App\Enum;

enum AppRoute: string
{
    case LOGIN = '/login';
    case REGISTER = '/register';
    case LOGOUT = '/logout';
    case PAGE_A = '/page-a';
    case PAGE_B = '/page-b';
}
