<div class="row">
    <div class="col-md-12">
        <x-alert />
        <div class="card">
            <div class="card-body">
                <x-form::open :action="route(COMPANY_STORE_ROUTE)" class="enter-index-form">
                    <input type="hidden" name="id" value="{{ old('id') }}">
                    <x-form::group :label="trans('company.name_company')">
                        <x-form::input name="name" :value="old('name')" required />
                    </x-form::group>

                    <div class="row">
                        <div class="col-4">
                            <x-form::group :label="trans('company.zip_code')">
                        <x-form::input name="post_code" :value="old('post_code')" required id="post_code" placeholder="例：100-0000"/>
                    </x-form::group>
                        </div>
                        <div class="col-md-4">
                            <x-form::group :label="trans('company.tel')">
                                <x-form::input name="tel" :value="old('tel')" required/>
                            </x-form::group>
                        </div>
                        <div class="col-md-4">
                            <x-form::group :label="trans('company.fax')">
                                <x-form::input name="fax" :value="old('fax')"/>
                            </x-form::group>
                        </div>
                    </div>

                    <x-form::group :label="trans('company.address_one')">
                        <x-form::input name="address" :value="old('address')" required />
                    </x-form::group>

                    <x-form::group :label="trans('company.address_two')">
                        <x-form::input name="address_more" :value="old('address_more')" />
                    </x-form::group>

                    <x-form::group :label="trans('company.regis_number')" r>
                        <x-form::input name="regis_number" :value="old('regis_number')" />
                    </x-form::group>

                    <div class="d-grid gap-2 d-md-block mt-5">
                        <a href="{{ route(COMPANY_ROUTE) }}" title=""
                            class="btn btn-outline-info btn-icon btn-icon-start">
                            <span>{{ trans('app.cancel') }}</span>
                        </a>
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                            <i data-acorn-icon="save"></i>
                            <span>{{ trans('company.save_change') }}</span>
                        </button>
                    </div>
                </x-form::open>
            </div>
        </div>
    </div>
</div>
