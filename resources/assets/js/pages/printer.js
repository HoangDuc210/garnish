import PDFMerger from 'pdf-merger-js/browser';

var PRINTER = (function() {
    let detailReceiptPrintButton = $('[data-btn="detail-receipt-print"]');
    let detailReceiptMarutoPrintButton = $('[data-btn="detail-receipt-maruto-print"]');
    let printRevenueAgentButton = $('[data-btn="revenue-agent-print"]');
    let printRevenueProductButton = $('[data-btn="revenue-product-print"]');
    let printN335ReceiptButton = $('[data-btn="receipt-print-n335"]');

    //Print detail receipt
    var printDetailReceipt = function () {
        detailReceiptPrintButton.on('click', function (e) {
            let url = $(this).data("url");
            let data = {
                id: $(this).data("id"),
            };

            return actionExport(url, data);

        });
    }

    //Print detail receipt
    var printDetailReceiptMaruto = function () {
        let countProductItem = $('.table-item-receipt').data("product");
        if (countProductItem > 12 || countProductItem === 0) {
            detailReceiptMarutoPrintButton.prop('disabled', true);
            detailReceiptMarutoPrintButton.attr('data-url', "");
            return false;
        }
        detailReceiptMarutoPrintButton.on('click', function (e) {
            let url = $(this).data("url");
            let data = {
                id: $(this).data("id"),
            };

            if (countProductItem > 12 || countProductItem === 0) return false;

            return actionExport(url, data);
        });
    }

    //Print revenue agent
    var printRevenueAgent = function () {
        printRevenueAgentButton.on('click', function (e) {
            let url = $(this).data("url");
            let data = {
                month: $(this).data("month"),
                agent: {
                    id: $(this).data("agent_id"),
                    code: $(this).data("agent_code"),
                }
            };

            return actionExport(url, data);
        });
    }

    //Print revenue agent
    var printRevenueProduct = function () {
        printRevenueProductButton.on('click', function (e) {
            let url = $(this).data("url");
            let data = {
                month_start: $(this).data("month_start"),
                month_end: $(this).data("month_end"),
                agent: {
                    id: $(this).data("agent_id"),
                    code: $(this).data("agent_code"),
                }
            };

            return actionExport(url, data);
        });
    }

    //Print n335 receipt
    var printN335Receipt = function () {
        let countProductItem = $('.table-item-receipt').data("product");
        if (countProductItem > 12 || countProductItem === 0) {
            printN335ReceiptButton.prop('disabled', true);
            printN335ReceiptButton.attr('data-url', "");
            return false;
        }
        printN335ReceiptButton.on('click', function (e) {
            let url = $(this).data("url");
            let data = {
                id: $(this).data("id"),
            };
            if (countProductItem > 12 || countProductItem === 0) return false;
            return actionExport(url, data);
        });
    }

    //Call ajax
    let actionExport = function (url, data, updateStatus = true, print = true) {
        GLOBAL_CONFIG.loadingCustom(true);
        //Call ajax
        return GLOBAL_CONFIG.callAjax(url, "POST", data)
        .done(function(response) {
            if (updateStatus) {
                updatePrintedPdf('/receipts/update/print-status', data);
            }
            let files = response.result;
            if (!Array.isArray(files)) {
                GLOBAL_CONFIG.loadingCustom(false);
                if (print) {
                    return printJS(files);
                }else{
                    return window.open(files);
                }

            }
            mergeMultiplePdf(files)
                .then(function (result) {
                    GLOBAL_CONFIG.loadingCustom(false);
                    if (print) {
                        let options = {
                            printable: result,
                        }
                        return printJS(options);
                    }else{
                        return window.open(result);
                    }
                });
        })
        .fail(function(error) {
            GLOBAL_CONFIG.loadingCustom(false);
        });
    }

    //Let merge multiple file pdf
    let mergeMultiplePdf = function(files) {
        const render = async () => {
            const merger = new PDFMerger();
            for(const file of files) {
                await merger.add(file)
            }
            const mergedPdf = await merger.saveAsBlob();
            let url = URL.createObjectURL(mergedPdf);
            return url;
        };
        return render();
    }

    let updatePrintedPdf = function (url, data) {
        GLOBAL_CONFIG.callAjax(url, 'POST',data)
        .done(function (response) {
            $('#print-status').addClass('btn-info').removeClass('btn-outline-danger').text('印刷済み');
        })
        .fail(function (error) {
            $('#print-status').removeClass('btn-outline').addClass('btn-outline-info').text('未印刷');
        });
    }

    //Print list-by-year-month
    let printListByYearMonth = function()
    {
        $('#list-by-year-month-print').on('click', function() {
            console.log(1);
            let data = {
                calculate_date: $('select[name="calculate_date"]').val()
            };
            let url = "/billings/print-list-by-year-month"
            return actionExport(url, data, false);
        });
    }

    //printListCollations
    let printListCollations = function()
    {
        $('#list-collations').on('click', function() {
            let data = {
                calculate_date: $('select[name="calculate_date"]').val(),
                billing_agent_id: $('select[name="billing_agent_id"]').val(),
            };
            let url = "/billings/print-list-collations"
            return actionExport(url, data, false, true);
        });
    }

    //previewListCollations
    let previewListCollations = function()
    {
        $('#preview-list-collations').on('click', function() {
            let data = {
                calculate_date: $('select[name="calculate_date"]').val(),
                billing_agent_id: $('select[name="billing_agent_id"]').val(),
            };
            let url = "/billings/preview-list-collations"
            return actionExport(url, data, false, false);
        });
    }

    //Print list by batch
    let printListByBatch = function()
    {
        $(document).on('click', '#print-list-by-batch', function (e) {
            e.preventDefault();
            let billingAgentIds = [];
            $(document).find('.input-item').each(function () {
                if ($(this).prop('checked')) {
                    billingAgentIds.push($(this).val());
                }
            });

            if (billingAgentIds.length === 0){
                let content = "レコードを選択してください?";
                return GLOBAL_CONFIG.sweetAlert("", content, true);
            }else{
                let data = {
                    billing_agent_ids: billingAgentIds,
                    calculate_date: $('select[name="calculate_date"]').val(),
                };
                let url = "/billings/print-list-by-batch"
                return actionExport(url, data, false, true);
            }
        });
    }

    //Print list by batch
    let previewListByBatch = function()
    {
        $(document).on('click', '#preview-list-by-batch', function (e) {
            e.preventDefault();
            let billingAgentIds = [];
            $(document).find('.input-item').each(function () {
                if ($(this).prop('checked')) {
                    billingAgentIds.push($(this).val());
                }
            });

            if (billingAgentIds.length === 0){
                let content = "レコードを選択してください?";
                return GLOBAL_CONFIG.sweetAlert("", content, true);
            }else{
                let data = {
                    billing_agent_ids: billingAgentIds,
                    calculate_date: $('select[name="calculate_date"]').val(),
                };
                let url = "/billings/preview-list-by-batch"
                return actionExport(url, data, false, false);
            }
        });
    }

    return {
        _: function () {
            printDetailReceipt();
            printDetailReceiptMaruto();
            printRevenueAgent();
            printRevenueProduct();
            printN335Receipt();
            printListByYearMonth();
            printListCollations();
            previewListCollations();
            printListByBatch();
            previewListByBatch();
        },
    };
})();

jQuery(function () {
    PRINTER._();
});