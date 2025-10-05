<?php

namespace App\Enums;

use TysonLaravel\Enums\Arrayable;
use TysonLaravel\Enums\InvokableCases;
use TysonLaravel\Enums\Options;
use TysonLaravel\Enums\Translable;

enum PrintStatus: int
{
    use InvokableCases;
    use Arrayable;
    use Options;
    use Translable;

    case PRINT = 0;

    case PRINTED = 1;
}
