<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class GenderEnum extends Enum
{
    #[Description('Laki - Laki')]
    const Male = 'L';

    #[Description('Perempuan')]
    const Female = 'P';
}
