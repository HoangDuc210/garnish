<?php

namespace App\Enums\Agent;

use TysonLaravel\Enums\Arrayable;
use TysonLaravel\Enums\InvokableCases;
use TysonLaravel\Enums\Options;
use TysonLaravel\Enums\Translable;

enum TaxationMethod: string
{
    use InvokableCases;
    use Arrayable;
    use Options;
    use Translable;

    case BILLING = 'billing';

    case VOUCHER = 'voucher';

    CASE ITEMIZED = 'itemized';

}
