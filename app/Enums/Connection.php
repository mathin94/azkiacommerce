<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Connection extends Enum
{
    const Ecommerce = 'mysql';

    const Backoffice = 'mysql_pos';
}
