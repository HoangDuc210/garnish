<!-- Layout Footer Start -->
<footer>
    <div class="footer-content">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <p class="mb-0 text-muted text-medium">POS System</p>
                </div>
                @if(env('APP_ENV') == 'local')
                <div class="col-12 col-sm-6">
                    <a href="{{route('language.switch', ['language' => 'vi'])}}" class="mb-0 text-muted text-medium">Language VI</a>
                </div>
                @endif
                <div class="col-sm-6 d-none d-sm-block"></div>
            </div>
        </div>
    </div>
</footer>
<!-- Layout Footer End -->
