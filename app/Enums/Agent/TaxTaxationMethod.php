<?php

declare(strict_types=1);

namespace App\Enums\Agent;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

final class TaxTaxationMethod extends Enum
{
    #[Description('請求課税')]
    const Billing = 'billing';

    #[Description('伝票課税')]
    const Voucher = 'voucher';

    #[Description('明細課税')]
    const Itemized = 'itemized';
}