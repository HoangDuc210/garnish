<link media="all" rel="preconnect" href="https://fonts.gstatic.com"/>
<link media="all" href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;700&display=swap" rel="stylesheet"/>
<link media="all" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet"/>

@include('_partials/style_page')

<script>
  const Laravel = {!! json_encode([
    'assets' => [
      'style' => [
        'vendor' => Vite::asset('resources/assets/scss/vendor.scss'),
        'billing' => Vite::asset('resources/assets/scss/pages/billing.scss'),
        'deposit' => Vite::asset('resources/assets/scss/pages/deposit.scss')
      ],
      'script' => [
        'vendor' => Vite::asset('resources/assets/js/vendor.js'),
        'billing' => Vite::asset('resources/assets/js/pages/billing.js')
      ],
    ]
  ]) !!};
</script>