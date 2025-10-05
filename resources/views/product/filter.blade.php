
<h2 class="small-title">{{ trans('product.filter') }}</h2>

<div class="card">
    <div class="card-body py-1">
        <x-form::open method="GET" class="enter-index-form">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <x-form::group :label="trans('product.code')">
                        <x-form::input name="code" :value="request('code')"/>
                    </x-form::group>
                </div>
                <div class="col-md-4">
                    <x-form::group :label="trans('product.name')">
                        <x-form::input name="name" :value="request('name')"/>
                    </x-form::group>
                </div>
                <div class="col-md-auto">
                    <x-form::group label="&nbsp;">
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                            <i data-acorn-icon="search"></i>
                            {{ trans('app.search') }}
                        </button>
                        <a href="{{ route(PRODUCT_ROUTE) }}" title=""
                            class="btn btn-outline-info btn-icon btn-icon-start">
                            <span>{{ trans('app.cancel') }}</span>
                        </a>
                        <a href="{{ route(PRODUCT_CREATE_ROUTE) }}" class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto">
                            <i data-acorn-icon="plus"></i>
                            <span>{{ trans('app.create_btn') }}</span>
                        </a>
                    </x-form::group>
                </div>
            </div>
        </x-form::open>
    </div>
</div>
