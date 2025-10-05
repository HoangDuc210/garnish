import setup from "datatables.net";
window.DataTable = setup(window, window.$);
import PDFMerger from 'pdf-merger-js/browser';

var BILLING = (function () {
    // Functions
    const makeUrlSearchParams = function (payload) {
        if (!payload) return "";

        Object.keys(payload).forEach(function (key) {
            let item = payload[key];
            if (Array.isArray(item)) {
                payload[key] = item.toString();
            }
        });

        return new URLSearchParams(payload).toString();
    };
    const formatDateTime = function (datetime, format) {
        format = format.replace(/yyyy/g, datetime.getFullYear());
        format = format.replace(
            /MM/g,
            ("0" + (datetime.getMonth() + 1)).slice(-2)
        );
        format = format.replace(/M/g, datetime.getMonth() + 1);
        format = format.replace(/dd/g, ("0" + datetime.getDate()).slice(-2));
        format = format.replace(/d/g, datetime.getDate());
        format = format.replace(/HH/g, ("0" + datetime.getHours()).slice(-2));
        format = format.replace(/H/g, datetime.getHours());
        format = format.replace(/mm/g, ("0" + datetime.getMinutes()).slice(-2));
        format = format.replace(/m/g, datetime.getMinutes());
        format = format.replace(/ss/g, ("0" + datetime.getSeconds()).slice(-2));

        return format;
    };
    const thousandSeparator = function (number) {
        if (number) {
            number = Number(number);
            return number.toLocaleString("en");
        }

        return number;
    };
    const toLabel = function (str) {
        if (!str) {
            return "";
        }
        return str;
    };
    const makeYearMonthInfo = function (yearMonth) {
        if (!yearMonth) {
            return "yyyy/MM/dd ~ yyyy/MM/dd";
        }

        var calculateDate = new Date(yearMonth);
        var firstDate = new Date(
            calculateDate.getFullYear(),
            calculateDate.getMonth(),
            1
        );
        var lastDate = new Date(
            calculateDate.getFullYear(),
            calculateDate.getMonth() + 1,
            0
        );

        return (
            formatDateTime(firstDate, "yyyy/MM/dd") +
            " ~ " +
            formatDateTime(lastDate, "yyyy/MM/dd")
        );
    };
    const getMonthLastDate = function (datetime) {
        return new Date(datetime.getFullYear(), datetime.getMonth() + 1, 0);
    };
    const makePageList = function (rowsCount, rowsPerPage) {
        var pageList = [];
        const pageCount =
            Math.ceil(rowsCount / rowsPerPage) >= 1
                ? Math.ceil(rowsCount / rowsPerPage)
                : 1;

        for (let page = 0; page < pageCount; page++) {
            let firstIndex = page * rowsPerPage;
            let lastIndex = page * rowsPerPage + rowsPerPage;
            pageList.push([firstIndex, lastIndex]);
        }

        return pageList;
    };

    // Constants
    const trans = Page.trans;

    // Initialize
    const initializeDataTable = function () {
        const dataTable = $(".data-table").DataTable({
            pageLength: 20,
            ordering: false,
            searching: false,
            bLengthChange: false,
            info: false,
            language: {
                paginate: {
                    first: "最初",
                    last: "最後",
                    next: "次",
                    previous: "前",
                },
                emptyTable: "テーブルにデータがありません",
            },
        });

        const initializePagination = () => {
            const info = dataTable.page.info();
            if (info) {
                $(".data-table-pagination-info .page-current").html(
                    info.page + 1
                );
                $(".data-table-pagination-info .page-count").html(info.pages);
            }
        };
        dataTable.on("page.dt", () => {
            initializePagination();
        });
        initializePagination();
    };

    const initializeDynamicPrinter = function (namePrefix, htmlString) {
        const printTrigger = $(`.${namePrefix}-print-trigger`);
        const previewTrigger = $(`.${namePrefix}-preview-trigger`);

        printTrigger.on("click", function () {
            const printerWindow = window.open("", "", "");
            printerWindow.document.writeln(htmlString);
            printerWindow.focus();
            setTimeout(function () {
                printerWindow.print();
                printerWindow.close();
            }, 1000);
        });
        previewTrigger.on("click", function () {
            const printerWindow = window.open("", "", "");
            printerWindow.document.writeln(htmlString);
            printerWindow.focus();
        });
    };
    const initializeCalculateDate = function () {
        const calculateDate = $("select[name='calculate_date']");
        const calculateDateInfo = $(".calculate-date-info");
        const calculateDateMonth = $(".calculate-date-month");
        const formattedDeadlineDate = $(".formatted-deadline-date");

        const initialize = function () {
            const deadlineDate = getMonthLastDate(
                new Date(calculateDate.val())
            );

            calculateDateInfo.html(makeYearMonthInfo(calculateDate.val()));
            calculateDateMonth.html(
                formatDateTime(new Date(calculateDate.val()), "M")
            );
            formattedDeadlineDate.html(
                formatDateTime(deadlineDate, "yyyy年 MM月 dd日")
            );
        };

        calculateDate.on("change", function () {
            initialize();
        });

        initialize();
    };
    const initializeBillingAgentInput = function () {
        var target = $("select[name='billing_agent_id']");
        var targetInput = $("input[name='input_billing_agent_id']");
        var selectedValue = target.attr("data-selectedValue");

        const request = GLOBAL_CONFIG.callAjax(
            "/agents/billing-agents",
            "GET",
            {}
        );
        request
            .done((response) => {
                var data = response.data;
                var blankOption = new Option("", "", true);
                blankOption.setAttribute("data-code", "");
                target.append(blankOption);

                for (let i = 0; i < data.length; i++) {
                    let newOption = new Option(
                        data[i].name,
                        data[i].id,
                        false,
                        data[i].id === Number(selectedValue)
                    );
                    newOption.setAttribute("data-code", data[i].code);
                    target.append(newOption);

                    // search input
                    if (data[i].id === Number(selectedValue)) {
                        targetInput.val(data[i].code);
                    }
                }
            })
            .fail(function (error) {
                console.log(error);
            });

        target.on("change", function (e) {
            let option = e.target.querySelector(
                `option[value='${e.target.value}']`
            );
            targetInput.val(option.getAttribute("data-code"));
        });
        targetInput.on("input", function (e) {
            let option = target.find(`option[data-code='${e.target.value}']`);
            target.val(option.attr("value"));
        });
    };
    const initializePrintTrigger = function () {
        var trigger = $("#print-billing-agent-year-month");

        trigger.on("click", function () {
          let url = "print-billing-agent-year-month"
          let data = {
            calculate_date: $('select[name="calculate_date"]').val(),
            billing_agent_id: $('select[name="billing_agent_id"]').val(),
          };
          actionExport(url, data);
        });
    };
    const initializeExportCsvTrigger = function () {
        var trigger = $(".export-csv-trigger");
        var billingAgentId = $("select[name='billing_agent_id']");
        var calculateDate = $("select[name='calculate_date']");

        trigger.on("click", function () {
            //Set option data ajax
            var url = trigger.attr("data-url");
            var params = {
                billing_agent_id: billingAgentId.val(),
                calculate_date: calculateDate.val(),
            };
            GLOBAL_CONFIG.loadingCustom(true);
            let request = GLOBAL_CONFIG.callAjax(url, "POST", params);
            request
                .done((response) => {
                    GLOBAL_CONFIG.loadingCustom(false);
                    window.open(response.data);
                })
                .fail(function (error) {
                  GLOBAL_CONFIG.loadingCustom(false);
                    console.log(error);
                });
        });
    };
    const initializeTableCheckTrigger = function () {
        const checkAllTrigger = $(".table-check-all-trigger");
        const uncheckAllTrigger = $(".table-uncheck-all-trigger");
        const tableCheck = $(".table-check");
        const tableCheckDisplay = $(".table-check-display");

        const checkDisplay = () => {
            const checkValues = $(".table-check:checked")
                .map(function () {
                    return $(this).val();
                })
                .get();

            if (checkValues.length > 0) {
                tableCheckDisplay.show();
            } else {
                tableCheckDisplay.hide();
            }
        };

        checkAllTrigger.on("click", function () {
            tableCheck.prop("checked", true);
            checkDisplay();
        });
        uncheckAllTrigger.on("click", function () {
            tableCheck.prop("checked", false);
            checkDisplay();
        });
        tableCheck.on("change", function () {
            checkDisplay();
        });

        checkDisplay();
    };

    const initializeListByBillingAgentYearMonthPrinter = function () {
        const calculateDate = $("select[name='calculate_date']");
        const deadlineDate = getMonthLastDate(new Date(calculateDate.val()));
        const company = Page.company;
        const billingAgent = Page.billingAgent;
        const billing = Page.billing ? Page.billing : {};
        const billingReceipts = Page.billingReceipts
            ? Page.billingReceipts
            : [];
        const rowsPerPage = 10;
        const rowsCount = billingReceipts.length;
        const pageList = makePageList(rowsCount, rowsPerPage);

        if (!billingAgent || !billingReceipts || !billing) return;

        const html = `
      <html>

      <head>
        <title>請求書ー括出力</title>
        <link rel="stylesheet" href="${Laravel.assets.style.vendor}">
        <link rel="stylesheet" href="${Laravel.assets.style.billing}">
      </head>
      
      <body>
        <div class="billing origin-container card">
          <div class="card-body py-3">
            ${pageList
                .map((page, pageIndex) => {
                    return `
                <div class="preview-container mt-5">

                  <div class="page-index">Page: ${pageIndex + 1}/${
                        pageList.length
                    }</div>
                
                  <div class="header text-center">
                    <div class="title">請 求 書</div>
                    <div class="deadline">
                      <span class="formatted-deadline-date">${formatDateTime(
                          deadlineDate,
                          "yyyy年 MM月 dd日"
                      )}</span>
                    締切分</div>
                  </div>

                  <div class="info row">

                    <div class="billing-agent col-6 item">
                      <div class="post-code">〒${toLabel(
                          billingAgent.post_code
                      )}</div>
                      <div class="address">${toLabel(
                          billingAgent.address
                      )}${toLabel(billingAgent.address_more)}</div>
                      <div class="name">${toLabel(billingAgent.name)} 御中</div>
                    </div>

                      <div class="company col-6 item">

                        <div class="address">${toLabel(
                            company.address
                        )}${toLabel(company.address_more)}</div>
                        <div class="name">${toLabel(company.name)}</div>
                        <div class="tel">TEL ${toLabel(company.tel)}</div>
                        <div class="fax">FAX ${toLabel(company.fax)}</div>
                        <div class="bank-account">【お振込先】${toLabel(
                            company.bank_account
                        )}</div>

                      </div>

                  </div>

                  <div class="sub-info">
                    <div>毎度ありがとうございます。</div>
                    <div>下記の通り御請求申し上げます。</div>
                  </div>

                  <div class="row table-container">
                    <div class="col-9">
                      <table class="table align-middle">
                        <thead>
                          <tr>
                            <th>${trans.last_billed_amount}</th>
                            <th>${trans.deposit_amount}</th>
                            <th>${trans.carried_forward_amount}</th>
                            <th>${trans.receipt_amount}</th>
                            <th>${trans.tax_amount}</th>
                            <th>${trans.billing_amount}</th>
                          </tr>
                        </thead>

                        <tbody>
                            <tr>
                              <td class="text-right">${thousandSeparator(
                                  billing.last_billed_amount
                              )}</td>
                              <td class="text-right">${thousandSeparator(
                                  billing.deposit_amount
                              )}</td>
                              <td class="text-right">${thousandSeparator(
                                  billing.carried_forward_amount
                              )}</td>
                              <td class="text-right">${thousandSeparator(
                                  billing.final_receipt_amount
                              )}</td>
                              <td class="text-right">${thousandSeparator(
                                  billing.tax_amount
                              )}</td>
                              <td class="text-right">${thousandSeparator(
                                  billing.billing_amount
                              )}</td>
                            </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="col-3 row stamp">
                      <div class="col-6 first">
                      </div>
                      <div class="col-6 second">
                      </div>
                    </div>
                  </div>


                  <table class="data-table table align-middle">
                    <thead>
                      <tr>
                        <th>${trans.transaction_date}</th>
                        <th>${trans.receipt_id}</th>
                        <th>${trans.deposit_amount}</th>
                        <th>${trans.receipt_total_amount}</th>
                        <th>${trans.receipt_memo}</th>
                      </tr>
                    </thead>

                    <tbody>
                      ${billingReceipts
                          .slice(page[0], page[1])
                          .map((billingReceipt, billingIndex) => {
                              return `
                          <tr
                            class="${
                                pageIndex === pageList.length - 1 &&
                                billingIndex ===
                                    billingReceipts.slice(page[0], page[1])
                                        .length -
                                        1
                                    ? "tr-bold"
                                    : ""
                            }"
                          >
                            <td class="text-center">${
                                billingReceipt.transaction_date
                            }</td>
                            <td class="text-center">${billingReceipt.id}</td>
                            <td class="text-right">
                              ${
                                  billingReceipt.is_deposit ||
                                  billingReceipt.is_total
                                      ? thousandSeparator(
                                            billingReceipt.deposit_amount
                                        )
                                      : ""
                              }
                            </td>
                            <td class="text-right">${thousandSeparator(
                                billingReceipt.total_amount
                            )}</td>
                            <td>${
                                billingReceipt.memo ? billingReceipt.memo : ""
                            }</td>
                          </tr>
                        `;
                          })
                          .join("")}
                    </tbody>
                  </table>

                </div>

                <div class="print-page-break"></div>
              `;
                })
                .join("")}

          </div>
        </div>
      </body>
      
      </html>      
    `;

        initializeDynamicPrinter("list-billing-agent-year-month", html);
    };
    const initializeListByYearMonthPrinter = function () {
        const calculateDate = $("select[name='calculate_date']");
        const billingYearMonth = formatDateTime(
            new Date(calculateDate.val()),
            "yyyy/MM"
        );
        const billings = Page.billings ? Page.billings : [];
        const totalBillings = Page.totalBillings ? Page.totalBillings : {};
        const rowsPerPage = 20;
        const rowsCount = billings.length;
        const pageList = makePageList(rowsCount, rowsPerPage);

        if (!billings || !totalBillings) return;

        const html = `
      <html>

      <head>
        <title>請求書ー括出力</title>
        <link rel="stylesheet" href="${Laravel.assets.style.vendor}">
        <link rel="stylesheet" href="${Laravel.assets.style.billing}">
      </head>
      
      <body>
        <div class="billing origin-container card">
          <div class="card-body py-3">
            ${pageList
                .map((page) => {
                    return `
                <div class="preview-container mt-5">

                  <div class="header right-header">
                    <div class="title">請 求 一 覧 表</div>
                    <div class="time">作成日: ${formatDateTime(
                        new Date(),
                        "yyyy/MM/dd HH:mm:ss"
                    )}</div>
                  </div>

                  <div class="info">
                    <div class="billing-date">請求年月: ${billingYearMonth}
                    </div>
                  </div>

                  <table class="data-table table align-middle">
                    <thead>
                      <tr>
                        <th>${trans.id}</th>
                        <th>${trans.billing_agent_name}</th>
                        <th>${trans.last_billed_amount}</th>
                        <th>${trans.deposit_amount}</th>
                        <th>${trans.carried_forward_amount}</th>
                        <th>${trans.receipt_amount}</th>
                        <th>${trans.tax_amount}</th>
                        <th>${trans.billing_amount}</th>
                      </tr>
                    </thead>

                    <tbody>
                      ${billings
                          .slice(page[0], page[1])
                          .map((billing) => {
                              return `
                          <tr>
                            <td>${billing.billing_agent?.code}</td>
                            <td>${billing.billing_agent?.name}</td>
                            <td class="text-right">${thousandSeparator(
                                billing.last_billed_amount
                            )}</td>
                            <td class="text-right">${thousandSeparator(
                                billing.deposit_amount
                            )}</td>
                            <td class="text-right">${thousandSeparator(
                                billing.carried_forward_amount
                            )}</td>
                            <td class="text-right">${thousandSeparator(
                                billing.final_receipt_amount
                            )}</td>
                            <td class="text-right">${thousandSeparator(
                                billing.tax_amount
                            )}</td>
                            <td class="text-right">${thousandSeparator(
                                billing.billing_amount
                            )}</td>
                          </tr>
                        `;
                          })
                          .join("")}

                      <tr>
                        <td class="text-center"></td>
                        <td class="tr-bold">【 総 合 計 】</td>
                        <td class="text-right">${thousandSeparator(
                            totalBillings.last_billed_amount
                        )}</td>
                        <td class="text-right">${thousandSeparator(
                            totalBillings.deposit_amount
                        )}</td>
                        <td class="text-right">${thousandSeparator(
                            totalBillings.carried_forward_amount
                        )}</td>
                        <td class="text-right">${thousandSeparator(
                            totalBillings.final_receipt_amount
                        )}</td>
                        <td class="text-right">${thousandSeparator(
                            totalBillings.tax_amount
                        )}</td>
                        <td class="text-right">${thousandSeparator(
                            totalBillings.billing_amount
                        )}</td>
                      </tr>
                    </tbody>
                  </table>

                </div>

                <div class="print-page-break"></div>
              `;
                })
                .join("")}

          </div>
        </div>
      </body>
      
      </html>      
    `;

        initializeDynamicPrinter("list-by-year-month", html);
    };
    const initializeListBillingAgentCollationsPrinter = function () {
        const calculateDate = $("select[name='calculate_date']");
        const company = Page.company;
        const billingAgent = Page.billingAgent;
        const collations = Page.collations ? Page.collations : [];
        const rowsPerPage = 15;
        const rowsCount = collations.length;
        const pageList = makePageList(rowsCount, rowsPerPage);

        if (!billingAgent || !collations) return;

        const html = `
      <html>

      <head>
        <title>請求照合表</title>
        <link rel="stylesheet" href="${Laravel.assets.style.vendor}">
        <link rel="stylesheet" href="${Laravel.assets.style.billing}">
      </head>
      
      <body>
        <div class="billing origin-container card">
          <div class="card-body py-3">
            ${pageList
                .map((page, pageIndex) => {
                    return `
                <div class="preview-container mt-5">

                  <div class="page-index">page${pageIndex + 1}</div>

                  <div class="print-repeat-header">
                    <div class="header text-center">
                      <div class="title">請 求 照 合 表</div>
                    </div>

                    <div class="info billing-agent">
                      ${billingAgent.name} 御中
                    </div>

                    <div class="intro">
                      <span>いつも大変お世話になっております。</span>
                      <span class="calculate-date-month">${formatDateTime(
                          new Date(calculateDate.val()),
                          "M"
                      )}</span>
                      <span>月の照合表を送りいたします。</span>
                      <br>
                      ご確認の程、宜しくお願い致します。
                    </div>

                    <div class="company company-title text-center">${
                        company.name
                    }</div>
                  </div>

                  <table class="data-table table align-middle print-repeat-body">
                    <thead>
                      <tr>
                        <th>${trans.transaction_date}</th>
                        <th>${trans.receipt_id_full}</th>
                        <th>${trans.receipt_total_amount_full}</th>
                        <th>${trans.receipt_memo}</th>
                      </tr>
                    </thead>

                    <tbody>
                      ${collations
                          .slice(page[0], page[1])
                          .map((collation, collationIndex) => {
                              return `
                          <tr
                            class="
                            ${collation.is_total ? "tr-bold" : ""}
                            ${collation.is_agent_title ? " agent-title" : ""}
                            "
                          >
                            <td class="text-center">${
                                collation.transaction_date
                            }</td>
                            <td class="text-center">${collation.id}</td>
                            <td class="text-right">${thousandSeparator(
                                collation.total_amount
                            )}</td>
                            <td>${collation.memo ? collation.memo : ""}</td>
                          </tr>
                        `;
                          })
                          .join("")}
                    </tbody>
                  </table>
                </div>

                <div class="print-page-break"></div>
              `;
                })
                .join("")}
          </div>
        </div>
      </body>
      
      </html>      
    `;

        initializeDynamicPrinter("list-billing-agent-collations", html);
    };
    const initializeListByBatchPrinter = function () {
        const trigger = $(".list-by-batch-print-trigger");
        const previewTrigger = $(".list-by-batch-preview-trigger");
        const company = Page.company;
        const calculateDate = $("select[name='calculate_date']");
        const deadlineDate = getMonthLastDate(new Date(calculateDate.val()));
        const print = function (data, previewOnly) {
            const html = `
        <html>

        <head>
          <title>請求書ー括出力</title>
          <link rel="stylesheet" href="${Laravel.assets.style.vendor}">
          <link rel="stylesheet" href="${Laravel.assets.style.billing}">
        </head>
        
        <body>
          <div class="billing origin-container card">
            <div class="card-body py-3">
            ${data
                .map((item) => {
                    const rowsPerPage = 10;
                    const rowsCount = item.billing_receipts.length;
                    const pageList = makePageList(rowsCount, rowsPerPage);

                    return `
                ${pageList
                    .map((page) => {
                        return `
                    <div class="preview-container mt-5">
                      <div class="header text-center">
                        <div class="title">請求書</div>
                        <div class="deadline">
                          <span class="formatted-deadline-date">${formatDateTime(
                              deadlineDate,
                              "yyyy年 MM月 dd日"
                          )}</span>
                          締切分
                        </div>
                      </div>
      
                      <div class="info row">
                        <div class="billing-agent col-6 item">
                          <div class="post-code">〒${toLabel(
                              item.billing_agent.post_code
                          )}</div>
                          <div class="address">${toLabel(
                              item.billing_agent.address
                          )}${toLabel(item.billing_agent.address_more)}</div>
                          <div class="name">${toLabel(
                              item.billing_agent.name
                          )} 御中</div>
                        </div>
    
                        <div class="company col-6 item">
                          <div class="address">${toLabel(
                              company.address
                          )}${toLabel(company.address_more)}</div>
                          <div class="name">${toLabel(company.name)}</div>
                          <div class="tel">TEL ${toLabel(company.tel)}</div>
                          <div class="fax">FAX ${toLabel(company.fax)}</div>
                          <div class="bank-account">【お振込先】${toLabel(
                              company.bank_account
                          )}</div>
                        </div>
                      </div>
        
                      <div class="sub-info">
                        <div>毎度ありがとうございます。</div>
                        <div>下記の通り御請求申し上げます。</div>
                      </div>
        
                      <div class="row table-container">
                        <div class="col-9">
                          <table class="table align-middle">
                            <thead>
                              <tr>
                                <th>${trans.last_billed_amount}</th>
                                <th>${trans.deposit_amount}</th>
                                <th>${trans.carried_forward_amount}</th>
                                <th>${trans.receipt_amount}</th>
                                <th>${trans.tax_amount}</th>
                                <th>${trans.billing_amount}</th>
                              </tr>
                            </thead>

                            <tbody>
                                <tr>
                                  <td class="text-right">${thousandSeparator(
                                      item.last_billed_amount
                                  )}</td>
                                  <td class="text-right">${thousandSeparator(
                                      item.deposit_amount
                                  )}</td>
                                  <td class="text-right">${thousandSeparator(
                                      item.carried_forward_amount
                                  )}</td>
                                  <td class="text-right">${thousandSeparator(
                                      item.final_receipt_amount
                                  )}</td>
                                  <td class="text-right">${thousandSeparator(
                                      item.tax_amount
                                  )}</td>
                                  <td class="text-right">${thousandSeparator(
                                      item.billing_amount
                                  )}</td>
                                </tr>
                            </tbody>
                          </table>
                        </div>
      
                        <div class="col-3 row stamp">
                          <div class="col-6 first">
                          </div>
                          <div class="col-6 second">
                          </div>
                        </div>
                      </div>
      
                      <table class="table align-middle">
                        <thead>
                          <tr>
                            <th>${trans.transaction_date}</th>
                            <th>${trans.receipt_id}</th>
                            <th>${trans.deposit_amount}</th>
                            <th>${trans.receipt_total_amount}</th>
                            <th>${trans.receipt_memo}</th>
                          </tr>
                        </thead>
      
                        <tbody>
                          ${item.billing_receipts
                              .slice(page[0], page[1])
                              .map(
                                  (billingReceipt) => `
                            <tr>
                              <td class="text-center">${
                                  billingReceipt.transaction_date
                              }</td>
                              <td class="text-center">${billingReceipt.id}</td>
                              <td class="text-right">
                                ${
                                    billingReceipt.is_deposit ||
                                    billingReceipt.is_total
                                        ? thousandSeparator(
                                              billingReceipt.deposit_amount
                                          )
                                        : ""
                                }
                              </td>
                              <td class="text-right">${thousandSeparator(
                                  billingReceipt.total_amount
                              )}</td>
                              <td>${
                                  billingReceipt.memo ? billingReceipt.memo : ""
                              }</td>
                            </tr>
                          `
                              )
                              .join("")}
                        </tbody>
                      </table>
                    </div>
                  <div class="print-page-break"></div>
                  `;
                    })
                    .join("")}
              `;
                })
                .join("")}
            </div>
          </div>
        </body>
        
        </html>      
      `;

            const printerWindow = window.open("", "", "");
            printerWindow.document.writeln(html);
            printerWindow.focus();
            setTimeout(function () {
                if (!previewOnly) {
                    printerWindow.print();
                    printerWindow.close();
                }
            }, 1000);
        };

        trigger.on("click", function () {
            const billingAgentIds = $(
                'input[name="billing_agent_ids[]"]:checked'
            )
                .map(function () {
                    return $(this).val();
                })
                .get();
            const calculateDate = $("select[name='calculate_date']").val();

            const params = makeUrlSearchParams({
                in___billing_agent_id: billingAgentIds,
                calculate_date: calculateDate,
            });

            const request = GLOBAL_CONFIG.callAjax(
                "/billings/search",
                "GET",
                params
            );
            request
                .done((response) => {
                    var data = response.data;
                    data.sort((a, b) => {
                        if (a.billing_agent?.code < b.billing_agent?.code) {
                            return -1;
                        }
                        if (a.billing_agent?.code > b.billing_agent?.code) {
                            return 1;
                        }
                        return 0;
                    });

                    print(data);
                })
                .fail(function (error) {
                    console.log(error);
                });
        });

        previewTrigger.on("click", function () {
            const billingAgentIds = $(
                'input[name="billing_agent_ids[]"]:checked'
            )
                .map(function () {
                    return $(this).val();
                })
                .get();
            const calculateDate = $("select[name='calculate_date']").val();

            const params = makeUrlSearchParams({
                in___billing_agent_id: billingAgentIds,
                calculate_date: calculateDate,
            });

            const request = GLOBAL_CONFIG.callAjax(
                "/billings/search",
                "GET",
                params
            );
            request
                .done((response) => {
                    var data = response.data;
                    data.sort((a, b) => {
                        if (a.billing_agent?.code < b.billing_agent?.code) {
                            return -1;
                        }
                        if (a.billing_agent?.code > b.billing_agent?.code) {
                            return 1;
                        }
                        return 0;
                    });

                    print(data, true);
                })
                .fail(function (error) {
                    console.log(error);
                });
        });
    };

    const initializeListByBatch = function () {
        const fetchTrigger = $(".fetch-trigger");

        fetchTrigger.on("change", function (e) {
            var value = e.target.value;
            window.location.replace(
                `/billings/list-by-batch?calculate_date=${value}`
            );
        });
    };

    //Call ajax
    let actionExport = function (url, data) {
        GLOBAL_CONFIG.loadingCustom(true);
        //Call ajax
        return GLOBAL_CONFIG.callAjax(url, "POST", data)
            .done(function (response) {
              let files = response.result;
              if (!Array.isArray(files)) {
                  GLOBAL_CONFIG.loadingCustom(false);
                  return printJS(files);
              }
              mergeMultiplePdf(files)
                  .then(function (result) {
                      let options = {
                          printable: result,
                      }
                      GLOBAL_CONFIG.loadingCustom(false);
                      return printJS(options);
                  });
            })
            .fail(function (error) {
                GLOBAL_CONFIG.loadingCustom(false);
            });
    };

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


    return {
        initialize: function () {
            initializeDataTable();
            initializeCalculateDate();
            initializeBillingAgentInput();
            initializePrintTrigger();
            initializeExportCsvTrigger();
            initializeTableCheckTrigger();

            initializeListByBillingAgentYearMonthPrinter();
            initializeListByYearMonthPrinter();
            initializeListBillingAgentCollationsPrinter();
            initializeListByBatchPrinter();

            initializeListByBatch();
        },
    };
})();

jQuery(function () {
    BILLING.initialize();
});
