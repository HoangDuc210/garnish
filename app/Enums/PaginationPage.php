<?php

namespace App\Enums;

use TysonLaravel\Enums\Arrayable;
use TysonLaravel\Enums\InvokableCases;
use TysonLaravel\Enums\Options;
use TysonLaravel\Enums\Translable;

enum PaginationPage: int
{
    use InvokableCases;
    use Arrayable;
    use Options;
    use Translable;

    case PAGE_SIZE_25 = 25;

    case PAGE_SIZE_50 = 50;

    CASE PAGE_SIZE_100 = 100;

}
