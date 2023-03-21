<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static IntroSlider()
 * @method static static AdSlider()
 * @method static static StaticBanner()
 */
final class SliderType extends Enum
{
    const IntroSlider  = 1;
    const AdSlider     = 2;
    const StaticBanner = 3;
}
