<div class="card mt-3">
    <div class="card-body">
        <div class="flex">
            <button data-url="{{ route(RECEIPT_AGENT_EXPORT_CSV_ROUTE) }}" data-receipt-id="{{ $receipt->id }}"
                btn-export-receipt-detail-csv class="btn btn-success">
                <i class="fa-solid fa-file-csv"></i>
                CSV 作成
            </button>
            <a href="{{ route(RECEIPT_ROUTE, $receipt->id - 1) }}" class="btn btn-warning">
                <i class="fa-solid fa-hand-point-left"></i>
                前の伝票
            </a>
            <a href="{{ route(RECEIPT_ROUTE, $receipt->id + 1) }}" class="btn btn-warning">
                <i class="fa-solid fa-hand-point-right"></i>
                次の伝票
            </a>
        </div>
    </div>
</div>
