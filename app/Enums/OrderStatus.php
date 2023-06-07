<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

final class OrderStatus extends Enum
{
    #[Description('Menunggu Pembayaran')]
    const WaitingPayment = 1;

    #[Description('Menunggu Konfirmasi')]
    const WaitingConfirmation = 2;

    #[Description('Dibayar')]
    const Paid = 3;

    #[Description('Paket Dikirim')]
    const PackageSent = 4;

    #[Description('Pesanan Selesai')]
    const Completed = 5;

    #[Description('Pesanan Dibatalkan')]
    const Canceled = 6;
}
