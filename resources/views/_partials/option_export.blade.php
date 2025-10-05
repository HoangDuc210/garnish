<div class="options-export">
    <div class="row">
        <div class="col-12 d-flex align-items-center flex-wrap">
            @if (!empty($preview))
                <button
                    @foreach ($preview as $key => $value)
                            data-{{ $key }}="{{ $value }}" @endforeach
                    class="btn btn-danger m-1">
                    <img src="{{ asset('img/icon/preview.png') }}" alt="" width="16" class="filter-invert-1">
                    {{ trans('app.preview') }}
                </button>
            @endif
            @if (!empty($print))
                <button
                    @foreach ($print as $key => $value)
                                data-{{ $key }}="{{ $value }}" @endforeach
                    title="" class="btn btn-secondary m-1">
                    <i class="fa-solid fa-print"></i>
                    {{ trans('app.printer') }}
                </button>
            @endif
            @if (!empty($exportCsv))
                <button
                    @foreach ($exportCsv as $key => $value)
                                data-{{ $key }}="{{ $value }}" @endforeach
                    class="btn btn-success m-1">
                    <i class="fa-solid fa-file-csv"></i>
                    {{ trans('app.export_csv') }}
                </button>
            @endif
            @if (!empty($checkboxNextPrevPage) && $checkboxNextPrevPage)
                @if ($prevPage)
                    <a href="" title=""
                        class="btn btn-warning m-1 btn-prev-page pe-none">
                        <i class="fa-solid fa-hand-point-left"></i>
                        {{ trans('app.prev_page') }}
                    </a>
                @endif
                @if ($nextPage)
                    <a href="" title=""
                        class="btn btn-warning m-1 btn-next-page pe-none">
                        <i class="fa-solid fa-hand-point-right "></i>
                        {{ trans('app.next_page') }}
                    </a>
                @endif
                <div class="form-group m-1">
                    <input type="checkbox" id="checkboxNextPrevPage" value="{{ $checkboxNextPrevPage['id'] }}"
                        data-url="{{ $checkboxNextPrevPage['url'] }}" name="checkboxNextPrevPage"
                        class="ms-1 form-check-input">
                    <label class="form-check-label ms-1"
                        for="checkboxNextPrevPage">{{ trans('app.checkbox_next_prev_page') }}</label>
                </div>
            @endif
            @if (!empty($printN335))
                <button
                    @foreach ($printN335 as $key => $value)
                                data-{{ $key }}="{{ $value }}" @endforeach
                    title="" class="btn btn-secondary m-1">
                    <i class="fa-solid fa-print"></i>
                    {{ trans('app.printer_n335') }}
                </button>
            @endif
            @if (!empty($removesItem))
                <button data-url="{{ $removesItem['url'] }}" data-btn-removes
                    data-message="{{ $removesItem['message'] }}" class="btn btn-danger m-1">
                    <i class="fa-solid fa-trash"></i>
                    {{ trans('app.remove_items') }}
                </button>
            @endif
            @if (!empty($btnCreate))
                <a href="{{ $btnCreate['url'] }}"  class="btn btn-primary m-1">
                    <i class="fa-solid fa-plus"></i>
                    <span>{{ trans('app.create_btn') }}</span>
                </a>
            @endif
            @if (!empty($btnEdit))
                <a href="{{ $btnEdit['url'] }}"  class="btn btn-primary m-1">
                    <i class="fa-regular fa-pen-to-square"></i>
                    {{ trans('app.edit') }}
                </a>
            @endif
        </div>
        <div class="col-2 select-limit opacity-0">
            @if (!empty($selectLimit))
                <x-form::select data-url="{{ $selectLimit['url'] }}" name="limit" :options="$selectLimit['limits']"
                    :selected="request('limit')" />
            @endif
        </div>
    </div>
</div>
