<!-- Vendor Scripts Start -->
<script src="{{ asset('js/lang-js.js') }}"></script>
@vite('resources/assets/js/vendor.js')
<script src="{{ asset('js/theme/base/loader.js') }}"></script>

<script src="{{ asset('js/theme/plugins/scrollbarByCount.js') }}"></script>
<script src="{{ asset('js/theme/icon/acorn-icons.js') }}"></script>
<script src="{{ asset('js/theme/icon/acorn-icons-interface.js') }}"></script>
<script src="{{ asset('js/theme/icon/acorn-icons-commerce.js') }}"></script>

@yield('js_vendor')
<!-- Vendor Scripts End -->
<!-- Template Base Scripts Start -->
<script src="{{ asset('js/theme/base/helpers.js') }}"></script>
<script src="{{ asset('js/theme/base/globals.js') }}"></script>
<script src="{{ asset('js/theme/base/nav.js') }}"></script>
<script src="{{ asset('js/theme/base/search.js') }}"></script>
<script src="{{ asset('js/theme/base/settings.js') }}"></script>
<script src="{{ asset('js/theme/plugins/checkall.js') }}"></script>
<!-- Template Base Scripts End -->
<!-- Page Specific Scripts Start -->
@vite('resources/assets/js/app.js')

@include('_partials.script_pages')

@stack('scripts')

<script src="{{ asset('js/theme/common.js') }}"></script>
<script src="{{ asset('js/theme/scripts.js?v=1.0') }}"></script>

<!-- Page Specific Scripts End -->
