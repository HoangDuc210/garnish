<x-alert />
<x-form::open :action="route(AGENT_STORE_ROUTE)" class="enter-index-form">
    <input type="hidden" name="id" value="{{ old('id', 0) }}" />
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <x-form::group :label="trans('agent.code')" required>
                                <x-form::input name="code" :value="old('code')" required class="input-number-validate" />
                            </x-form::group>
                        </div>
                        <div class="col-md-9">
                            <x-form::group :label="trans('agent.billing_name')" required>
                                <x-form::input name="name" :value="old('name')" required />
                            </x-form::group>
                        </div>
                    </div>

                    <x-form::group :label="trans('agent.name_kana')" r>
                        <x-form::input name="name_kana" :value="old('name_kana')" />
                    </x-form::group>

                    <div class="row">
                        <div class="col-md-3">
                            <x-form::group :label="trans('agent.zip_code')">
                                <x-form::input name="post_code" :value="old('post_code')" placeholder="例：000-0000" />
                            </x-form::group>
                        </div>
                        <div class="col-md">
                            <x-form::group :label="trans('agent.tel')">
                                <x-form::input name="tel" class="input-mark-number" :value="old('tel')" />
                            </x-form::group>
                        </div>
                        <div class="col-md">
                            <x-form::group :label="trans('agent.fax')">
                                <x-form::input name="fax" :value="old('fax')" />
                            </x-form::group>
                        </div>
                        <div class="col-12 text-danger">※ハイフンを入れ、半角数字のみで入力してください</div>
                    </div>

                    <x-form::group :label="trans('agent.address')">
                        <x-form::input name="address" :value="old('address')" />
                    </x-form::group>

                    <x-form::group :label="trans('agent.address_more')">
                        <x-form::input name="address_more" :value="old('address_more')" />
                    </x-form::group>

                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <x-form::group :label="trans('agent.billing_cycle')">
                                <x-form::input-group name="closing_date" :value="old('closing_date', 31)" :append="trans('app.day')" />
                            </x-form::group>
                        </div>
                        <div class="col-auto">
                            <div class="pt-4 text-danger">
                                {{ trans('agent.billing_cycle_help_text') }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form::group :label="trans('agent.price_format')">
                                <select class="form-select" name="fraction_rounding_code">
                                    @foreach (\App\Enums\PriceFormat::options() as $key => $value)
                                        <option value={{ $key }}
                                            @if (old('fraction_rounding_code', \App\Enums\PriceFormat::FOUR_DOWN_FIVE_UP()) === $key) selected @endif>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </x-form::group>
                        </div>
                        <div class="col-md-6">
                            <x-form::group :label="trans('agent.tax_category')">
                                <select class="form-select" name="tax_type_code">
                                    @foreach (\App\Enums\TaxCategory::options() as $key => $value)
                                        <option value={{ $key }}
                                            @if (old('tax_type_code', \App\Enums\TaxCategory::TAX_INCLUDED()) === $key) selected @endif>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </x-form::group>
                        </div>
                        <div class="col-md-6">
                            <x-form::group :label="trans('agent.price_after_tax_format')">
                                <select class="form-select" name="tax_fraction_rounding_code">
                                    @foreach (\App\Enums\PriceFormat::options() as $key => $value)
                                        <option value={{ $key }}
                                            @if (old('tax_fraction_rounding_code', \App\Enums\PriceFormat::FOUR_DOWN_FIVE_UP()) === $key) selected @endif>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </x-form::group>
                        </div>
                        <div class="col-md-6">
                            <x-form::group :label="trans('agent.consumption_taxation_method')">
                                <select class="form-select" name="tax_taxation_method_code">
                                    @foreach (\App\Enums\Agent\TaxationMethod::options() as $key => $value)
                                        <option value={{ $key }}
                                            @if (old('tax_taxation_method_code', \App\Enums\Agent\TaxationMethod::BILLING()) === $key) selected @endif>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </x-form::group>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="form-check col-form-label">
                        <input class="form-check-input" type="checkbox" @if (old('is_parent', 0)) checked @endif
                            name="is_parent" id="check-agent-id">
                        <label class="form-check-label" for="check-agent-id">
                            得意先と請求先が同じです。
                        </label>
                    </div>

                    <x-form::group :label="trans('agent.billing_name')">
                        <select name="agent[id]" class="form-select agent-select"
                            @if (old('agent.id')) data-agent-selected="{{ old('agent.id') }}" @endif>
                        </select>
                    </x-form::group>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form::group label="{{ trans('agent.tax') }}">
                                <x-form::input-group name="tax_rate" :value="old('tax_rate', CONSUMPTION_TAX)" append="%" />
                            </x-form::group>
                        </div>
                        <div class="col-md-6">
                            <x-form::group label="{{ trans('agent.collection_rate') }}">
                                <x-form::input-group name="collection_rate" :value="old('collection_rate', '0.00')" append="%" />
                            </x-form::group>
                        </div>
                    </div>

                    <x-form::group label="{{ trans('agent.print_type') }}">
                        <x-form::radio name="print_type" :list="\App\Enums\PrintType::options()" :value="old('print_type')" :checked="old('print_type', \App\Enums\PrintType::SET_OF_4())" />
                    </x-form::group>

                    <x-form::group label="請求元">
                        <span class="form-control">{{ !empty($company) ? $company->name : '' }}</span>
                    </x-form::group>

                    <div class="d-grid gap-2 d-md-block mt-4">
                        <a href="{{ route(AGENT_ROUTE) }}" title=""
                            class="btn btn-outline-info btn-icon btn-icon-start me-2">
                            <i data-acorn-icon="arrow-bottom-left"></i>
                            <span>{{ trans('app.back_to_list') }}</span>
                        </a>
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                            <i data-acorn-icon="save"></i>
                            <span>{{ trans('app.save') }}</span>
                        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </x-form::open>
