<x-form::open :action="route(ACCESS_HISTORY_ROUTE)" method="GET" class="enter-index-form">
    <div class="card mb-3 mb-md-5">
        <div class="card-body">

            <div class="row">
                <div class="col-2">
                    <label for="">{{ trans('accessHistory.filter_login_at') }}</label>
                </div>

                <div class="col-6 d-flex justify-content-center align-items-center">
                    <x-form::input name="from_login_at" :value="request('from_login_at')" data-toggle="date" />
                    <span class="date-filter-middle">~</span>
                    <x-form::input name="to_login_at" :value="request('to_login_at')" data-toggle="date" />
                </div>

                <div class="col-4">
                    <label>{{ trans('accessHistory.notice') }}</label>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-2">
                    <label for="">{{ trans('accessHistory.name') }}</label>
                </div>

                <div class="col-3">
                    <x-form::select name="user_id" select2 :options="$userOptions" :selected="request('user_id', $defaultUserOption)" />
                </div>

                {{-- <div class="col-4">
                    <x-form::radio name="order_direction" :list="['asc' => trans('app.order_asc'), 'desc' => trans('app.order_desc')]" :checked="request('to_login_at', 'desc')" />
                </div> --}}
            </div>

            <br>

            <div class="d-grid gap-2 d-md-block text-center">
                <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                    <i data-acorn-icon="search"></i>
                    <span>{{ trans('app.aggregate') }}</span>
                </button>

                <a href="{{ route(ACCESS_HISTORY_ROUTE) }}" class="btn btn-outline-info btn-icon btn-icon-start">
                    <span>{{ trans('app.cancel') }}</span>
                </a>
            </div>
        </div>
    </div>
</x-form::open>
