<?php

declare(strict_types=1);

namespace App\Enums\Receipt;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

final class Type extends Enum
{
    #[Description('通常')]
    const Common = 'common';

    #[Description('チェーンストア')]
    const ChainStore = 'chain_store';
}