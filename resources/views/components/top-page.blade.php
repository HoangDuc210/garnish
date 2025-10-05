<div class="page-title-container mb-3" id="top-page">
    <div class="row">
        <div class="col mb-2">
            <h1 class="mb-2 pb-0 display-4" id="page-name">
                {{ $name }}
            </h1>
            {{-- <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="#" class="muted-link">
                        <span class="text-small align-middle me-2">HOME PAGE</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="acorn-icons acorn-icons-chevron-right undefined"><path d="M7 4L12.6464 9.64645C12.8417 9.84171 12.8417 10.1583 12.6464 10.3536L7 16"></path></svg>
                    </a>
                </li>
                <li class="list-inline-item">
                    <a href="#" class="muted-link">
                        <span class="text-small align-middle me-2">LIBRARY</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="acorn-icons acorn-icons-chevron-right undefined"><path d="M7 4L12.6464 9.64645C12.8417 9.84171 12.8417 10.1583 12.6464 10.3536L7 16"></path></svg>
                    </a>
                </li>
                <li class="list-inline-item">
                    <a href="#" class="muted-link">
                        <span class="text-small align-middle me-2">DATA</span>
                    </a>
                </li>
            </ul> --}}
        </div>
        @if (isset($slot) && $slot->isNotEmpty())
        <div class="col-12 col-sm-auto d-flex align-items-center justify-content-end">
            {{ $slot }}
        </div>
        @endif
    </div>
</div>
