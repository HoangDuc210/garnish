<?php

namespace App\Enums;

use TysonLaravel\Enums\Arrayable;
use TysonLaravel\Enums\InvokableCases;
use TysonLaravel\Enums\Options;
use TysonLaravel\Enums\Translable;

enum ReceiptFilter: int
{
    use InvokableCases;
    use Arrayable;
    use Options;
    use Translable;

    case NEW = 1;

    case DISABLED = 0;
}
