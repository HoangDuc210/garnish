<?php

namespace App\Enums;

use TysonLaravel\Enums\Arrayable;
use TysonLaravel\Enums\InvokableCases;
use TysonLaravel\Enums\Options;
use TysonLaravel\Enums\Translable;

enum PriceFormat: string
{
    use InvokableCases;
    use Arrayable;
    use Options;
    use Translable; 

    case FOUR_DOWN_FIVE_UP = 'four_down_five_up';

    case TRUNCATION = 'truncation';

    case ROUNDING_UP = 'rounding_up';
}
