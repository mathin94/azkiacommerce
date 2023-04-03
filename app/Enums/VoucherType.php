<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

final class VoucherType extends Enum
{
    #[Description('Diskon Biaya Pengiriman')]
    const ShippingCostDiscount = 1;

    #[Description('Diskon Harga')]
    const PriceDiscount = 2;
}
