<?php

declare(strict_types=1);

namespace App\Enums\Agent;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

final class TaxType extends Enum
{
    #[Description('外税')]
    const TaxExcluded = 'tax_excluded';

    #[Description('内税')]
    const TaxIncluded = 'tax_included';

    #[Description('非課税')]
    const TaxExempted = 'tax_exempted';
}