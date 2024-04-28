<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TableSearchEnum extends Enum
{
    const PER_PAGE = 20;
    const PAGE = 1;
    const SORT = 'asc';
    const SORT_DESC = 'desc';
    const COLUMN = 'id';

}
