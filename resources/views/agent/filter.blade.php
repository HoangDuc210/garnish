<h2 class="small-title">{{ trans('agent.filter') }}</h2>
<x-form::open method="GET" class="enter-index-form">
    <div class="card mb-4">
        <div class="card-body pb-3">
            <div class="row align-items-center col">
                <div class="col-md-2">
                    <x-form::group :label="trans('agent.filters.code')">
                        <x-form::input name="code" :value="request('code')" />
                    </x-form::group>
                </div>
                <div class="col-md-5">
                    <x-form::group :label="trans('agent.filters.name')">
                        <x-form::input name="name" :value="request('name')" />
                    </x-form::group>
                </div>

                <div class="col-md-7">
                    <x-form::group :label="trans('agent.filters.parent')">
                        <x-form::input name="parent" :value="request('parent')" />
                    </x-form::group>
                </div>
                <div class="col-md-5">
                    <x-form::group label="&nbsp;">
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                            <i data-acorn-icon="search"></i>
                            {{ trans('app.search') }}
                        </button>
                        <a href="{{ route(AGENT_ROUTE) }}" class=" btn btn-outline-info btn-icon btn-icon-start">
                            <span>{{ trans('app.cancel') }}</span>
                        </a>
                        <a href="{{ route(AGENT_CREATE_ROUTE) }}"
                            class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto">
                            <i data-acorn-icon="plus"></i>
                            <span>{{ trans('app.create_btn') }}</span>
                        </a>
                    </x-form::group>
                </div>
            </div>
        </div>
    </div>
</x-form::open>
