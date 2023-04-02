<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class VoucherType extends Enum
{
    const ShippingCostDiscount = 1;
    const PriceDiscount = 2;
}
