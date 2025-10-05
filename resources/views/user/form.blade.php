
<div class="row">
    <div class="col-12 col-lg-8">
        <x-alert/>
        <div class="card">
            <div class="card-body">
                <x-form::open :action="route(USER_STORE_ROUTE)" class="enter-index-form">
                    <input type="hidden" name="id" value="{{ old('id', 0) }}">
                    <div class="row">
                        <div class="col-md-4">
                            <x-form::group :label="trans('user.username')" required>
                                <x-form::input name="username" autocomplete='false' :value="old('username')" required/>
                            </x-form::group>
                        </div>
                        <div class="col-md-4">
                            <x-form::group :label="trans('user.role')">
                                <x-form::select name="role" select2
                                                :options="\App\Enums\Role::options()"
                                                :selected="old('role', \App\Enums\Role::STAFF())" />
                            </x-form::group>
                        </div>
                    </div>

                    <x-form::group :label="trans('user.name')" required>
                        <x-form::input name="name" :value="old('name')" autocomplete='false' required/>
                    </x-form::group>

                    @isset($user)
                    <div class="alert alert-info py-1 mt-3 mb-1">
                        {{ trans('user.help_change_pass') }}
                    </div>
                    @endisset
                    <x-form::group :label="trans('user.password')" required class="position-relative">
                        <span class="text-danger rule-password " style="font-size: 12px;">※パスワードは８文字以上の文字、数字の組み合わせで入力してください。</span>
                        <x-form::input type="password" name="password" id="password" autocomplete='false' :required="!isset($user)" />
                    </x-form::group>
                    <x-form::group :label="trans('user.password_confirmation')" required>
                        <x-form::input type="password" name="password_confirmation" data-rule-equalTo="#password" required/>
                    </x-form::group>

                    <div class="d-grid gap-2 d-md-block mt-5">
                        <a href="{{ route(USER_ROUTE) }}" title="" class="btn btn-outline-info btn-icon btn-icon-start">
                            <i data-acorn-icon="arrow-bottom-left"></i>
                            <span>{{ trans('app.back_to_list') }}</span>
                        </a>
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                            <i data-acorn-icon="save"></i>
                            <span>{{ trans('app.save_changes') }}</span>
                        </button>
                    </div>
                </x-form::open>
            </div>
        </div>
    </div>
</div>

