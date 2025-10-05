<?php

declare(strict_types=1);

namespace App\Enums\Agent;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

final class TaxFractionRounding extends Enum
{
    /**
     * Rounds num away from zero when it is half way there
     *
     * Ex:
     * 9.4 = 9;
     * 9.5 = 10
     */
    #[Description('四捨五入')]
    const FourDownFiveUp = 'four_down_five_up';

    /**
     * Take only the integer part
     *
     * Ex: 9.1 = 9
     */
    #[Description('切り捨て')]
    const Truncation = 'truncation';

    /**
     * Rounds up to zero decimal places
     *
     * Ex: 9.1 = 10
     */
    #[Description('切り上げ')]
    const RoundingUp = 'rounding_up';
}