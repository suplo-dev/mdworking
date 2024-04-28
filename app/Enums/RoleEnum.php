<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class RoleEnum extends Enum
{
    const ADMIN = 'admin';
    const CUSTOMER = 'customer';
}
