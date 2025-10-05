<x-alert />
<div class="card">
    <div class="card-body">
        <x-form::open :action="route(PRODUCT_STORE_ROUTE)" class="enter-index-form">
            <input type="hidden" name="id" value="{{ old('id', 0) }}">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-4">
                            <x-form::group :label="trans('product.code')" required>
                                <x-form::input name="code" :value="old('code')" required />
                            </x-form::group>
                        </div>
                        <div class="col-md-8">
                            <x-form::group :label="trans('product.name')" required>
                                <x-form::input name="name" :value="old('name')" required />
                            </x-form::group>
                        </div>
                        <div class="col-12">
                            <x-form::group :label="trans('product.name_kana')">
                                <x-form::input name="name_kana" :value="old('name_kana')"/>
                            </x-form::group>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <x-form::group :label="trans('product.price')" >
                                <x-form::input type="text" name="price" :value="old('price')"
                                data-rule-digits="true"  class="input-number-validate"/>
                            </x-form::group>
                        </div>
                        <div class="col-md-4">
                            <x-form::group :label="trans('product.quantity')">
                                <x-form::input type="text" name="quantity" :value="old('quantity')"
                                    data-rule-digits="true" class="input-number-validate"/>
                            </x-form::group>
                        </div>
                        <div class="col-md-4">
                            <x-form::group :label="trans('product.unit')">
                                <select name="unit_id" id="" class="form-select">
                                    @foreach($units as $unit)
                                    <option @if(old('unit_id') == $unit->id) selected @endif value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </x-form::group>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-block mt-3">
                <a href="{{ route(PRODUCT_ROUTE) }}" title=""
                    class="btn btn-outline-info btn-icon btn-icon-start me-2">
                    <i data-acorn-icon="arrow-bottom-left"></i>
                    <span>{{ trans('app.back_to_list') }}</span>
                </a>
                <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                    <i data-acorn-icon="save"></i>
                    <span>{{ trans('app.save') }}</span>
                </button>
            </div>
        </x-form::open>
    </div>
</div>
