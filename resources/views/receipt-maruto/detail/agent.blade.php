<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-8">
                <x-form::group class="row align-items-center">
                    <div class="col-2">
                        <label for="">{{ trans('receipt.code') }}</label>
                    </div>
                    <div class="col-4 readonly-custom">
                        <span class="form-control">{{ $receipt->code }}</span>
                    </div>
                    <div class="col-2">
                        <select name="" id="" class="form-select">
                            <option value="" selected>売上</option>
                        </select>
                    </div>
                </x-form::group>
                <x-form::group class="row align-items-center">
                    <div class="col-2">
                        <label for="">{{ trans('receipt.transaction_date') }}</label>
                    </div>
                    <div class="col-4">
                        <span class="form-control">{{ $receipt->transaction_date_fm }}</span>
                    </div>
                </x-form::group>
                <x-form::group class="row align-items-center">
                    <div class="col-2">
                        <label for="">{{ trans('agent.name_and_code_agent') }}</label>
                    </div>
                    <div class="col-4 readonly-custom">
                        <span class="form-control">{{ $agent->code }}</span>
                    </div>
                    <div class="col-6">
                        <span class="form-control">{{ $agent->name }}</span>
                    </div>
                </x-form::group>
                <x-form::group class="row align-items-center">
                    <div class="col-2">
                        <label for="">{{ trans('agent.zip_code') }}</label>
                    </div>
                    <div class="col-4 readonly-custom">
                        <span class="form-control">{{ $agent->post_code }}</span>
                    </div>
                    <div class="col-2 text-start">
                        <label for="">{{ trans('agent.tel') }}</label>
                    </div>
                    <div class="col-4 readonly-custom">
                        <span class="form-control">{{ $agent->tel }}</span>
                    </div>
                </x-form::group>
                <x-form::group class="row align-items-center">
                    <div class="col-2 text-start">
                        <label for="">{{ trans('agent.fax') }}</label>
                    </div>
                    <div class="col-4 readonly-custom">
                        <span class="form-control">{{ $agent->fax }}</span>
                    </div>
                    <div class="col-2 text-start">
                        <label for="">{{ trans('agent.billing_cycle') }}</label>
                    </div>
                    <div class="col-4 readonly-custom">
                        <span class="form-control">{{ $agent->closing_date }}</span>
                    </div>
                </x-form::group>
                <x-form::group class="row align-items-center">
                    <div class="col-2">
                        <label for="">{{ trans('app.address') }}</label>
                    </div>
                    <div class="col-10 readonly-custom">
                        <span class="form-control">{{ $agent->address }}</span>
                    </div>
                </x-form::group>
                <x-form::group class="row align-items-center">
                    <div class="col-2">
                        <label for="">{{ trans('receipt.agent_name') }}</label>
                    </div>
                    <div class="col-10 readonly-custom">
                        <span class="form-control">{{ $agent->name }}</span>
                    </div>
                </x-form::group>
            </div>
            <div class="col-4">
                <p class="btn @if($receipt->print_status) btn-info @else btn-outline-danger @endif" id="print-status">
                    {{ \App\Enums\PrintStatus::tryFrom($receipt->print_status)->label() }}
                </p>
            </div>
        </div>
    </div>
</div>
