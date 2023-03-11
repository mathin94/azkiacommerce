<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Draft()
 * @method static static Publish()
 */
final class PostStatus extends Enum
{
    const Draft   = 1;
    const Publish = 2;
}
