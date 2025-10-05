<table {{ $attributes->merge(['class' => 'table table-striped table-hover']) }}>
@isset($thead)
    <thead>
        <tr>
            {{ $thead }}
        </tr>
    </thead>
@endif
    {{ $slot }}
</table>
