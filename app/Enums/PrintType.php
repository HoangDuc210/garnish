<?php

namespace App\Enums;

use TysonLaravel\Enums\Arrayable;
use TysonLaravel\Enums\InvokableCases;
use TysonLaravel\Enums\Options;
use TysonLaravel\Enums\Translable;

enum PrintType: int
{
    use InvokableCases;
    use Arrayable;
    use Options;
    use Translable;

    case SET_OF_4 = 4;

    case SET_OF_2 = 2;
}
