<?php

declare(strict_types=1);

use App\Enums\VoucherType;

return [

    VoucherType::class => [
        VoucherType::PriceDiscount => 'Administrador',
        VoucherType::ShippingCostDiscount => 'Súper administrador',
    ],

];
