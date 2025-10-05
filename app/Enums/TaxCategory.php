<?php

namespace App\Enums;

use TysonLaravel\Enums\Arrayable;
use TysonLaravel\Enums\InvokableCases;
use TysonLaravel\Enums\Options;
use TysonLaravel\Enums\Translable;

enum TaxCategory: string
{
    use InvokableCases;
    use Arrayable;
    use Options;
    use Translable;

    case TAX_INCLUDED = 'tax_included'; 

    case TAX_EXCLUDED = 'tax_excluded';

    case TAX_EXEMPTED = 'tax_exempted';
}
