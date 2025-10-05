@vite('resources/assets/scss/vendor.scss')
@vite('resources/assets/vendor/datatables.css')
@vite(['resources/assets/scss/template.scss', 'resources/assets/scss/custom.scss'])

@switch(request()->route()->getName())
    @case(RECEIPT_MARUTO_CREATE_ROUTE)
    @case(RECEIPT_MARUTO_EDIT_ROUTE)
    @case(RECEIPT_CREATE_ROUTE)
    @case(RECEIPT_EDIT_ROUTE)
        @vite('resources/assets/scss/pages/receipt.scss')
    @break

    @case(BILLING_LIST_BY_BILLING_AGENT_YEAR_MONTH_ROUTE)
    @case(BILLING_LIST_BY_YEAR_MONTH_ROUTE)
    @case(BILLING_LIST_BILLING_AGENT_COLLATIONS_ROUTE)
    @case(BILLING_LIST_BY_BATCH_ROUTE)
        @vite('resources/assets/scss/pages/billing.scss')
    @break

    @case(DEPOSIT_ROUTE)
        @vite('resources/assets/scss/pages/deposit.scss')
    @break
@endswitch
