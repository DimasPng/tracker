<?php

namespace App\Enum;

enum ActivityAction: string
{
    case LOGIN = 'login';
    case LOGOUT = 'logout';
    case REGISTRATION = 'registration';
    case PAGE_VIEW_A = 'page-view-a';
    case PAGE_VIEW_B = 'page-view-b';
    case CLICK_BUY_COW = 'click-buy-cow';
    case CLICK_DOWNLOAD = 'click-download';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
