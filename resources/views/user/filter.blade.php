<h2 class="small-title">{{ trans('app.filter') }}</h2>

<div class="card mb-5">
    <div class="card-body py-3">
        <x-form::open method="GET" class="enter-index-form">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <x-form::group :label="trans('user.username')">
                        <x-form::input name="username" :value="request('username')" />
                    </x-form::group>
                </div>
                <div class="col-md-2">
                    <x-form::group :label="trans('user.role')">
                        <x-form::select name="role" select2 :options="\App\Enums\Role::options()" :selected="request('role', \App\Enums\Role::STAFF())" />
                    </x-form::group>
                </div>
                <div class="col-md-auto">
                    <x-form::group label="&nbsp;">
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                            <i data-acorn-icon="search"></i>
                            {{ trans('app.search') }}
                        </button>
                        <a href="{{ route(USER_ROUTE) }}" class="btn btn-outline-info btn-icon btn-icon-start">
                            {{ trans('app.cancel') }}</a>
                        <a href="{{ route(USER_CREATE_ROUTE) }}"
                            class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto">
                            <i data-acorn-icon="plus"></i>
                            <span>{{ trans('app.create_btn') }}</span>
                        </a>
                    </x-form::group>
                </div>
            </div>
        </x-form::open>
    </div>
</div>
