<?php

namespace App\Enums;

use TysonLaravel\Enums\Arrayable;
use TysonLaravel\Enums\InvokableCases;
use TysonLaravel\Enums\Options;
use TysonLaravel\Enums\Translable;

enum Role: int
{
    use InvokableCases;
    use Arrayable;
    use Options;
    use Translable;

    case STAFF = 1;

    case ADMIN = 9;
}
