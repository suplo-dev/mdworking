<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PermissionEnum extends Enum
{
    const VIEW_USER = 'view user';
    const ADD_USER = 'add user';
    const UPDATE_USER = 'update user';

    const VIEW_ADS_FB = 'view ads fb';
    const ADD_ADS_FB = 'add ads fb';
    const UPDATE_ADS_FB = 'update ads fb';

    const VIEW_ADS_GG = 'view ads gg';
    const ADD_ADS_GG = 'add ads gg';
    const UPDATE_ADS_GG = 'update ads gg';

    public static function all(): array
    {
        return [self::VIEW_USER, self::ADD_USER, self::UPDATE_USER, self::VIEW_ADS_FB, self::ADD_ADS_FB, self::VIEW_ADS_GG, self::UPDATE_ADS_FB, self::ADD_ADS_GG, self::UPDATE_ADS_GG];
    }
}
