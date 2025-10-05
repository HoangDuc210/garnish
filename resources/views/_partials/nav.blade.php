<div class="nav-content container d-flex">
    <!-- Logo Start -->
    <div class="logo position-relative">
        <a href="{{ route(DASHBOARD_ROUTE) }}">
            <img src="{{ asset('img/logo.png') }}" alt="logo" />
        </a>
    </div>
    <!-- Logo End -->

    <!-- User Menu Start -->
    <div class="user-container d-flex">
        <a href="#" class="d-flex user position-relative" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img class="profile" alt="profile" src="{{ asset('img/profile/default-user.webp') }}" />
            <div class="name">{{ auth()->user()->name }}</div>
        </a>
        <div class="dropdown-menu dropdown-menu-end user-menu wide">
            {{-- <div class="row mb-3 ms-0 me-0">
                <div class="col-12 ps-1 mb-2">
                    <div class="text-extra-small text-primary">ACCOUNT</div>
                </div>
                <div class="col-6 ps-1 pe-1">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#">User Info</a>
                        </li>
                        <li>
                            <a href="#">Preferences</a>
                        </li>
                    </ul>
                </div>
                <div class="col-6 pe-1 ps-1">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#">Security</a>
                        </li>
                        <li>
                            <a href="#">Billing</a>
                        </li>
                    </ul>
                </div>
            </div> --}}
            <div class="row mb-1 ms-0 me-0">
                {{-- <div class="col-12 p-1 mb-3 pt-3">
                    <div class="separator-light"></div>
                </div> --}}
                {{-- <div class="col-6 ps-1 pe-1"> --}}
                    {{-- <ul class="list-unstyled">
                        <li>
                            <a href="#">
                                <i data-acorn-icon="help" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">Help</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i data-acorn-icon="file-text" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">Docs</span>
                            </a>
                        </li>
                    </ul> --}}
                {{-- </div> --}}
                <div class="col-12 pe-1 ps-1">
                    <ul class="list-unstyled">
                        {{-- <li>
                            <a href="#" target="_blank">
                                <i data-acorn-icon="gear" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">Intro</span>
                            </a>
                        </li> --}}
                        <li class="text-end">
                            <a href="{{ route(LOGOUT_ROUTE) }}">
                                <i data-acorn-icon="logout" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">{{ trans('app.logout') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- User Menu End -->

    <!-- Menu Start -->
    <div class="menu-container flex-grow-1">
        <ul id="menu" class="menu">
    </div>
    <!-- Menu End -->

    <!-- Mobile Buttons Start -->
    <div class="mobile-buttons-container">
        <!-- Menu Button Start -->
        <a href="#" id="mobileMenuButton" class="menu-button">
            <i data-acorn-icon="menu"></i>
        </a>
        <!-- Menu Button End -->
    </div>
    <!-- Mobile Buttons End -->
</div>
<div class="nav-shadow"></div>
