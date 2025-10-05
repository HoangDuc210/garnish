<?php

namespace App\Enums;

use TysonLaravel\Enums\Arrayable;
use TysonLaravel\Enums\InvokableCases;
use TysonLaravel\Enums\Options;
use TysonLaravel\Enums\Translable;

enum SendMoneyType: string
{
    use InvokableCases;
    use Arrayable;
    use Options;
    use Translable;

    case CASH = 'cash';
    case CHECK = 'check';
    case BANK_TRANSFER = 'bank_transfer';
    case COMMISSION = 'commission';
    case HAND_PRINT = 'hand_print';
    case ADJUSTMENT = 'adjustment';

}
