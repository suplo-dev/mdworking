<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ConstantEnum extends Enum
{
    const PERMISSIONS = [
        'user' => [
            'value' => [
                'add' => [
                    'value' => 'add user',
                    'label' => 'Thêm',
                ],
                'view' => [
                    'value' => 'view user',
                    'label' => 'Xem',
                ],
                'update' => [
                    'value' => 'update user',
                    'label' => 'Chỉnh sửa',
                ],
            ],
            'label' => 'Người dùng',
            'hasChild' => false,
        ],
        'campaign' => [
            'value' => [
                'facebook' => [
                    'value' => [
                        'add' => [
                            'value' => 'add ads fb',
                            'label' => 'Thêm',
                        ],
                        'view' => [
                            'value' => 'view ads fb',
                            'label' => 'Xem',
                        ],
                        'update' => [
                            'value' => 'update ads fb',
                            'label' => 'Chỉnh sửa',
                        ],
                    ],
                    'label' => 'Facebook',
                ],
                'google' => [
                    'value' => [
                        'add' => [
                            'value' => 'add ads gg',
                            'label' => 'Thêm',
                        ],
                        'view' => [
                            'value' => 'view ads gg',
                            'label' => 'Xem',
                        ],
                        'update' => [
                            'value' => 'update ads gg',
                            'label' => 'Chỉnh sửa',
                        ],
                    ],
                    'label' => 'Google',
                ],
            ],
            'label' => 'Quảng cáo',
            'hasChild' => true,
        ],
    ];

}
