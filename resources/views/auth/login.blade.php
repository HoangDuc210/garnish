<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-url-prefix="/" data-footer="true" data-color="light-blue">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>POS-System Control Panel</title>
    <meta name="description" content=""/>
    @include('_partials.head')
</head>
<body class="h-100 p-0">
<div id="root" class="h-100">
    <div class="container-fluid position-relative">
            <!-- Right Side Start -->
            <div class="custom-modal-login pb-4 px-4 pt-0 p-lg-0 ">
                <div class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5">
                    <div class="sw-lg-50 px-5">
                        <div class="pb-4">
                            <a href="" class="d-block m-auto text-center">
                                <img src="{{ asset('img/logo.png') }}" alt="">
                            </a>
                        </div>
                        <div>
                            <x-form::open action="{{ route(LOGIN_ROUTE) }}" class="tooltip-end-bottom" id="loginForm">
                                <div class="mb-3 filled form-group tooltip-end-top">
                                    <i data-acorn-icon="user"></i>
                                    <input class="form-control" placeholder="{{ trans('app.login.username') }}" type="text" name="username" value="{{ old('username') }}" required/>
                                </div>
                                <div class="mb-3 filled form-group tooltip-end-top">
                                    <i data-acorn-icon="lock-off"></i>
                                    <input class="form-control pe-7" name="password" type="password" placeholder="{{ trans('app.login.password') }}" required/>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-lg btn-primary m-auto">{{ trans('app.login.btn_login') }}</button>
                                </div>
                            </x-form::open>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right Side End -->
    </div>
</div>

@include('_partials.scripts')
</body>
</html>
