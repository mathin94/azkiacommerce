<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

final class OrderStatus extends Enum
{
    #[Description('Menunggu Pembayaran')]
    const WaitingPayment = 1;

    #[Description('Dibayar')]
    const Paid = 2;

    #[Description('Paket Dikirim')]
    const PackageSent = 3;

    #[Description('Pesanan Selesai')]
    const Completed = 4;

    #[Description('Pesanan Dibatalkan')]
    const Canceled = 4;
}
