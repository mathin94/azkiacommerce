<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 */
final class DiscountType extends Enum
{
    #[Description('Flash Sale')]
    const FlashSale = 1;

    #[Description('Diskon Normal')]
    const NormalDiscount = 2;
}
