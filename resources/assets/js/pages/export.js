var EXPORT = (function() {
    let previewAgentRevenueButton = $('[data-btn="revenue-agent-preview"]');
    let previewProductRevenueButton = $('[data-btn="revenue-product-preview"]');
    let exportButton = $('[attr-export]');
    let btnExportReceiptListCSV = $('[data-btn=receipt-export-list-csv]');
    let btnExportReceiptDetailCSV = $('[data-btn="detail-receipt-export-csv"]');
    let exportCsvProductRevenueButton = $('[data-btn="revenue-product-export-csv"]');
    let exportCsvAgentRevenueButton = $('[data-btn="revenue-agent-export-csv"]');
    let exportReceiptMarutoListCsvButton = $('[data-btn="receipt-maruto-export-csv"]');
    let exportDetailReceiptMarutoCsvButton = $('[data-btn="detail-receipt-maruto-export-csv"]');
    let exportAgentCsvButton = $('[data-btn="agent-export-csv"]');
    let exportProductCsvButton = $('[data-btn="product-export-csv"]');
    let nameSession = String(window.location.pathname).replace("/", "");

    //Preview agent revenue
    var previewAgentRevenue = function() {
        previewAgentRevenueButton.on('click', function() {
            let month = $(this).data('month');
            let code = $(this).data('code');
            let agentId = $(this).data('agent_id');
            let url = $(this).data('url');

            // Setup options ajax
            let data = {
                month: month,
                code: code,
                agent: {
                    id: agentId
                }
            }

            //Call ajax
            return actionExport(url, data);
        });
    }

    //Export receipt agent
    var exportReceiptAgent = function() {
        exportButton.on('click', function() {
            let receiptId = $(this).data('receipt_id');
            let url = $(this).data('url');

            // Setup options ajax
            let data = {
                receipt_id: receiptId
            }

            //Call ajax
            return actionExport(url, data);
        });
    }

    //Preview product revenue
    var previewProductRevenue = function() {
        previewProductRevenueButton.on('click', function() {
            let url = $(this).data('url');

            let data = {
                id: $(this).data('id'),
                month_start: $(this).data('month_start'),
                month_end: $(this).data('month_end'),
                agent: {
                    id: $(this).data('agent_id'),
                    code: $(this).data('agent_code'),
                },
            }

            return actionExport(url, data);
        });
    }

    //Export Receipt List CSV
    var exportReceiptListCSV = function () {
        btnExportReceiptListCSV.on("click", function () {
            let checkboxAll = sessionStorage.getItem('checkboxAll'+nameSession);
            //Set option data ajax
            let url = $(this).data("url");
            let data = {};

            checkboxAll ? data['ids'] = 'all' : data['ids'] = CHECKBOX_LIST.dataTable();
            if (!data.ids || data.ids.length === 0) {
                let content = '選択した伝票がありませんので選択してください。';
                return GLOBAL_CONFIG.sweetAlert("", content, true);
            }
            //Action export csv
            return actionExport(url, data);
        });
    };

    //Export CSV
    var exportReceiptDetailCsv = function () {
        btnExportReceiptDetailCSV.on("click", function () {
            let id = $(this).data("id");
            let url = $(this).data("url");
            //Check data checkbox input
            let data = {
                id: id,
            };

            return actionExport(url, data);
        });
    };

    //Preview product revenue
    var exportCsvProductRevenue = function() {
        exportCsvProductRevenueButton.on('click', function() {
            let url = $(this).data('url');

            let data = {
                month_start: $(this).data('month_start'),
                month_end: $(this).data('month_end'),
                agent: {
                    id: $(this).data('agent_id'),
                    code: $(this).data('agent_code'),
                },
            }

            return actionExport(url, data);
        });
    }

    //Export csv agent revenue
    var exportCsvAgentRevenue = function() {
        exportCsvAgentRevenueButton.on('click', function() {
            let month = $(this).data('month');
            let code = $(this).data('code');
            let agentId = $(this).data('agent_id');
            let url = $(this).data('url');

            // Setup options ajax
            let data = {
                month: month,
                code: code,
                agent: {
                    id: agentId
                }
            }

            //Call ajax
            return actionExport(url, data);
        });
    }

    //Export Receipt List CSV
    var exportReceiptMarutoListCSV = function () {
        exportReceiptMarutoListCsvButton.on("click", function () {
            let checkboxAll = sessionStorage.getItem('checkboxAll');
            //Set option data ajax
            let url = $(this).data("url");
            let data = {};

            checkboxAll ? data['all'] = true : data['ids'] = CHECKBOX_LIST.dataTable();
            if (!data.ids || data.ids.length === 0) {
                let content = '選択した伝票がありませんので選択してください。';
                return GLOBAL_CONFIG.sweetAlert("", content, true);
            }
            //Action export csv
            return actionExport(url, data);
        });
    };

    //Export detail Receipt maruto CSV
    var exportDetailReceiptMarutoCSV = function () {
        exportDetailReceiptMarutoCsvButton.on("click", function () {
            //Set option data ajax
            let url = $(this).data("url");
            let data = {
                id: $(this).data("id"),
            };

            return actionExport(url, data);
        });
    };

    //Export receipt agent
    var exportAgentCsv = function() {
        exportAgentCsvButton.on('click', function() {
            let checkboxAll = sessionStorage.getItem('checkboxAll'+nameSession);
            //Set option data ajax
            let url = $(this).data("url");
            let data = {};

            checkboxAll ? data['ids'] = 'all' : data['ids'] = CHECKBOX_LIST.dataTable();
            if (!data.ids || data.ids.length === 0) {
                let content = '選択した伝票がありませんので選択してください。';
                return GLOBAL_CONFIG.sweetAlert("", content, true);
            }
            //Action export csv
            return actionExport(url, data);
        });
    }

    //Export receipt agent
    var exportProductCsv = function() {
        exportProductCsvButton.on('click', function() {
            let checkboxAll = sessionStorage.getItem('checkboxAll'+nameSession);
            //Set option data ajax
            let url = $(this).data("url");
            let data = {};

            checkboxAll ? data['ids'] = 'all' : data['ids'] = CHECKBOX_LIST.dataTable();
            if (!data.ids || data.ids.length === 0) {
                let content = '選択した伝票がありませんので選択してください。';
                return GLOBAL_CONFIG.sweetAlert("", content, true);
            }
            //Action export csv
            return actionExport(url, data);
        });
    }

    //Call ajax
    let actionExport = function (url, data) {
        GLOBAL_CONFIG.loadingCustom(true);
        //Call ajax
        GLOBAL_CONFIG.callAjax(url, "POST", data)
        .done(function(response) {
            GLOBAL_CONFIG.loadingCustom(false);
            return window.open(response.result);
        })
        .fail(function(error) {
            GLOBAL_CONFIG.loadingCustom(false);
        });
    }

    return {
        _: function() {
            exportProductCsv();
            exportAgentCsv();
            previewAgentRevenue();
            exportReceiptAgent();
            exportReceiptListCSV();
            exportReceiptDetailCsv();
            previewProductRevenue();
            exportCsvProductRevenue();
            exportCsvAgentRevenue();
            exportReceiptMarutoListCSV();
            exportDetailReceiptMarutoCSV();
        }
    };
})();
jQuery(document).ready(function($) {
    EXPORT._();
});