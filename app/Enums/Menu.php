<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Menu extends Enum
{
    const LANDING_PAGE = [
        [
            'routeName' => 'about',
            'label' => 'Về MDConnect',
        ],
        [
            'routeName' => 'news',
            'label' => 'Tin tức',
        ],
        [
            'routeName' => 'network',
            'label' => 'Mạng lưới',
        ],
        [
            'routeName' => 'career',
            'label' => 'Tuyển dụng',
        ],
    ];
    const DASHBOARD = [
        [
            'routeName' => 'dashboard',
            'label' => 'Trang chủ',
            'iconClass' => 'fa-home',
        ],
        [
            'routeName' => 'user.index',
            'label' => 'Quản lí tài khoản',
            'iconClass' => 'fa-user',
            'permission' => PermissionEnum::VIEW_USER,
        ],
        [
            'routeName' => 'campaign',
            'label' => 'Quản lí quảng cáo',
            'iconClass' => 'fa-earth',
            'children' => [
                [
                    'routeName' => 'campaign.facebook.index',
                    'label' => 'Facebook',
                    'iconClass' => 'fa-brands fa-facebook',
                    'permission' => PermissionEnum::VIEW_ADS_FB,
                    'routeRelated' => ['campaign.facebook.add'],
                ],
                [
                    'routeName' => 'campaign.google.index',
                    'label' => 'Google',
                    'iconClass' => 'fa-brands fa-google',
                    'permission' => PermissionEnum::VIEW_ADS_GG,
                    'routeRelated' => ['campaign.google.add', 'campaign.google.detail', 'campaign.google.ads.index'],
                ],
            ],
        ],
    ];
}
