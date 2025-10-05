@switch(request()->route()->getName())
    @case(COMPANY_ROUTE)
        @vite('resources/assets/js/pages/searchPost.js')
    @break

    @case(DEPOSIT_ROUTE)
        @vite('resources/assets/js/pages/agent.js')
        @vite('resources/assets/js/pages/deposit.js')
    @break

    @case(DEPOSIT_EDIT_ROUTE)
    @case(DEPOSIT_CREATE_ROUTE)
        @vite('resources/assets/js/pages/repeater_deposit.js')
        @vite('resources/assets/js/pages/modal_search.js')
    @break

    @case(BILLING_LIST_BY_BILLING_AGENT_YEAR_MONTH_ROUTE)
    @case(BILLING_LIST_BY_YEAR_MONTH_ROUTE)
    @case(BILLING_LIST_BILLING_AGENT_COLLATIONS_ROUTE)
    @case(BILLING_LIST_BY_BATCH_ROUTE)
        @vite('resources/assets/js/pages/billing.js')
        @vite('resources/assets/js/pages/printer.js')

    @break

    @case(AGENT_EDIT_ROUTE)
    @case(AGENT_CREATE_ROUTE)
        @vite('resources/assets/js/pages/agent.js')
        @vite('resources/assets/js/pages/searchPost.js')
    @break

    @case(PRODUCT_ROUTE)
    @case(AGENT_ROUTE)
        @vite('resources/assets/js/pages/export.js')
    @break

    @case(RECEIPT_MARUTO_ROUTE)
    @case(RECEIPT_ROUTE)
    @case(REVENUE_AGENT_ROUTE)
    @case(REVENUE_PRODUCT_ROUTE)
        @vite('resources/assets/js/pages/export.js')
        @vite('resources/assets/js/pages/printer.js')
        @vite('resources/assets/js/pages/modal_search.js')
    @break

    @case(RECEIPT_MARUTO_DETAIL_ROUTE)
    @case(RECEIPT_DETAIL_ROUTE)
        @vite('resources/assets/js/pages/export.js')
        @vite('resources/assets/js/pages/printer.js')
        @vite('resources/assets/js/pages/prev_next_page_url.js')
    @break

    @case(RECEIPT_MARUTO_CREATE_ROUTE)
    @case(RECEIPT_CREATE_ROUTE)
        @vite('resources/assets/js/pages/receipt.js')
        @vite('resources/assets/js/pages/unit.js')
        @vite('resources/assets/js/pages/modal_search.js')

    @break

    @case(RECEIPT_EDIT_ROUTE)
    @case(RECEIPT_DUPLICATE_ROUTE)
    @case(RECEIPT_MARUTO_EDIT_ROUTE)
    @case(RECEIPT_MARUTO_DUPLICATE_ROUTE)
        @vite('resources/assets/js/pages/receipt.js')
        @vite('resources/assets/js/pages/unit.js')
        @vite('resources/assets/js/pages/modal_search.js')

    @break
@endswitch
