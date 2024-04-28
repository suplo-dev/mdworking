<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TableHeaderEnum extends Enum
{
    const USER = [
        [
            'column' => '',
            'label' => '#',
            'sortable' => false,
        ],
        [
            'column' => 'name',
            'label' => 'Họ tên',
            'sortable' => true,
        ],
        [
            'column' => 'email',
            'label' => 'Email',
            'sortable' => true,
        ],
        [
            'column' => 'phone',
            'label' => 'SĐT',
            'sortable' => true,
        ],
        [
            'column' => 'created_at',
            'label' => 'Ngày tạo',
            'sortable' => true,
        ],
    ];
    const CAMPAIGN_FACEBOOK = [
        [
            'column' => 'status',
            'label' => 'Tắt/Bật',
            'sortable' => true,
        ],
        [
            'column' => 'name',
            'label' => 'Tên chiến dịch',
            'sortable' => true,
        ],
        [
            'column' => 'result',
            'label' => 'Kết quả',
            'sortable' => true,
        ],
        [
            'column' => 'reach',
            'label' => 'Người tiếp cận',
            'sortable' => true,
        ],
        [
            'column' => 'impression',
            'label' => 'Lượt hiển thị',
            'sortable' => true,
        ],
        [
            'column' => 'cost_per_result',
            'label' => 'Chi phí trên mỗi kết quả',
            'sortable' => true,
        ],
        [
            'column' => 'amount_spent',
            'label' => 'Số tiền đã chi tiêu',
            'sortable' => true,
        ],
        [
            'column' => 'ended_at',
            'label' => 'Kết thúc',
            'sortable' => true,
        ],
    ];
    const CAMPAIGN_GOOGLE = [
        [
            'column' => '',
            'label' => '#',
            'sortable' => false,],
        [
            'column' => 'name',
            'label' => 'Tên chiến dịch',
            'sortable' => true,],
        [
            'column' => 'type',
            'label' => 'Loại chiến dịch',
            'sortable' => true,],
        [
            'column' => 'click',
            'label' => 'Số nhấp chuột',
            'sortable' => true,],
        [
            'column' => 'ctr',
            'label' => 'CTR',
            'sortable' => true,],
        [
            'column' => 'avg_cpc',
            'label' => 'CPC Trung bình',
            'sortable' => true,],
        [
            'column' => 'amount_spent',
            'label' => 'Chi phí',
            'sortable' => true,],
        [
            'column' => '',
            'label' => 'Xem chi tiết',
            'sortable' => false,
        ],
    ];
    const CAMPAIGN_GOOGLE_DETAIL = [
        [
            'column' => '',
            'label' => '#',
            'sortable' => false,],
        [
            'column' => 'name',
            'label' => 'Nhóm quảng cáo',
            'sortable' => true,],
        [
            'column' => 'click',
            'label' => 'Click',
            'sortable' => true,],
        [
            'column' => 'ctr',
            'label' => 'CTR',
            'sortable' => true,],
        [
            'column' => 'avg_cpc',
            'label' => 'CPC Tr.B',
            'sortable' => true,],
        [
            'column' => 'amount_spent',
            'label' => 'Chi phí',
            'sortable' => true,],
    ];
}
