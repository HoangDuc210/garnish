<?php

namespace App\Enums;

use TysonLaravel\Enums\Arrayable;
use TysonLaravel\Enums\InvokableCases;
use TysonLaravel\Enums\Options;
use TysonLaravel\Enums\Translable;

enum ProductStatus: int
{
    use InvokableCases;
    use Arrayable;
    use Options;
    use Translable;

    case DISABLED = 0;

    case AVAILABLE = 1;

    case OUT_OF_STOCK = 9;
}
