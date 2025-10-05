<?php

declare(strict_types=1);

namespace App\Enums\Deposit;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

final class Type extends Enum
{
    #[Description('現金')]
    const Cash = 'cash';

    #[Description('小切手')]
    const Check = 'check';

    #[Description('振込')]
    const BankTransfer = 'bank_transfer';

    #[Description('手数料')]
    const Commission = 'commission';

    #[Description('手形')]
    const HandPrint = 'hand_print';

    #[Description('入金調整')]
    const Adjustment = 'adjustment';
}