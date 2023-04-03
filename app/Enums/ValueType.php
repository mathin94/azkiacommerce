<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

final class ValueType extends Enum
{
    #[Description('Persentase (%)')]
    const Percentage = 1;

    #[Description('Nominal (Rp)')]
    const Amount = 2;
}
