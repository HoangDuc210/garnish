<!-- Menu Start -->
<div class="col-auto d-none d-lg-flex">
    <ul class="sw-25 side-menu mb-0 primary" id="menuSide">
        <li>
            <a href="#" data-bs-target="#services">
                <i data-acorn-icon="grid-1" class="icon" data-acorn-size="18"></i>
                <span class="label">
                    {{ trans('nav.daily_work') }}
                </span>
            </a>
            <ul>
                <li>
                    <a href="{{ route(RECEIPT_ROUTE, ['transaction_start_date' => Util::addParamReceipt()]) }}" @if (request()->route()->getName() == RECEIPT_ROUTE) class="active" @endif>
                        <i data-acorn-icon="car" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('nav.delivery_list') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route(RECEIPT_MARUTO_ROUTE, ['transaction_start_date' => Util::addParamReceipt()]) }}" @if (request()->route()->getName() == RECEIPT_MARUTO_ROUTE) class="active" @endif>
                        <i data-acorn-icon="form-check" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('nav.delivery_maruto') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route(REVENUE_AGENT_ROUTE) }}" @if (request()->route()->getName() == REVENUE_AGENT_ROUTE) class="active" @endif>
                        <i data-acorn-icon="activity" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('nav.sale_figures_by_customer') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route(REVENUE_PRODUCT_ROUTE) }}" @if (request()->route()->getName() == REVENUE_PRODUCT_ROUTE) class="active" @endif>
                        <i data-acorn-icon="chart-4" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('nav.sale_figures_by_product') }}
                        </span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#" data-bs-target="#services">
                <i data-acorn-icon="grid-1" class="icon" data-acorn-size="18"></i>
                <span class="label">{{ trans('nav.ledger_management') }}</span>
            </a>
            <ul>
                <li>
                    <a href="{{ route(AGENT_ROUTE) }}" @if (request()->route()->getName() == AGENT_ROUTE) class="active" @endif>
                        <i data-acorn-icon="home" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('nav.agent_management') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route(PRODUCT_ROUTE) }}" @if (request()->route()->getName() == PRODUCT_ROUTE) class="active" @endif>
                        <i data-acorn-icon="database" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('nav.product_management') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route(UNIT_ROUTE) }}" @if (request()->route()->getName() == UNIT_ROUTE) class="active" @endif>
                        <i data-acorn-icon="folders" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('nav.wholesale_management') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route(COMPANY_ROUTE) }}" @if (request()->route()->getName() == COMPANY_ROUTE) class="active" @endif>
                        <i data-acorn-icon="tag" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('nav.company_management') }} 
                        </span>
                    </a>
                </li>
            </ul>
        </li>
        @if(auth()->user()->isAdmin())
        <li>
            <a href="#" data-bs-target="#services">
                <i data-acorn-icon="grid-1" class="icon" data-acorn-size="18"></i>
                <span class="label">
                    {{ trans('nav.admin_tools') }}
                </span>
            </a>
            <ul>
                <li>
                    <a href="{{ route(USER_ROUTE) }}" @if (request()->route()->getName() == USER_ROUTE) class="active" @endif>
                        <i data-acorn-icon="user" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('nav.user_management') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a
                        href="{{ route(BILLING_LIST_BY_BILLING_AGENT_YEAR_MONTH_ROUTE) }}"
                        @if (request()->route()->getName() == BILLING_LIST_BY_BILLING_AGENT_YEAR_MONTH_ROUTE) class="active" @endif
                    >
                        <i data-acorn-icon="clipboard" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('billing.listByBillingAgentYearMonth') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a
                        href="{{ route(BILLING_LIST_BY_YEAR_MONTH_ROUTE) }}"
                        @if (request()->route()->getName() == BILLING_LIST_BY_YEAR_MONTH_ROUTE) class="active" @endif
                    >
                        <i data-acorn-icon="clipboard" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('billing.listByYearMonth') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a
                        href="{{ route(BILLING_LIST_BILLING_AGENT_COLLATIONS_ROUTE) }}"
                        @if (request()->route()->getName() == BILLING_LIST_BILLING_AGENT_COLLATIONS_ROUTE) class="active" @endif
                    >
                        <i data-acorn-icon="clipboard" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('billing.listBillingAgentCollations') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route(BILLING_LIST_BY_BATCH_ROUTE) }}"
                        @if (request()->route()->getName() == BILLING_LIST_BY_BATCH_ROUTE) class="active" @endif>
                        <i data-acorn-icon="clipboard" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('billing.listByBatch') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route(DEPOSIT_CREATE_ROUTE) }}"
                        @if (request()->route()->getName() == DEPOSIT_CREATE_ROUTE) class="active" @endif>
                        <i data-acorn-icon="clipboard" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('nav.deposit_create') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route(DEPOSIT_ROUTE) }}"
                        @if (request()->route()->getName() == DEPOSIT_ROUTE) class="active" @endif>
                        <i data-acorn-icon="clipboard" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('nav.deposit') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route(ACCESS_HISTORY_ROUTE) }}"
                        @if (request()->route()->getName() == ACCESS_HISTORY_ROUTE) class="active" @endif>
                        <i data-acorn-icon="shield-check" class="icon d-none" data-acorn-size="18"></i>
                        <span class="label">
                            {{ trans('nav.ACCESS_HISTORY') }}
                        </span>
                    </a>
                </li>
            </ul>
        </li>
        @endif
    </ul>

</div>
<!-- Menu End -->
