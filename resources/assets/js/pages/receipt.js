import Inputmask from "inputmask";
var RECEIPT = (function () {
    let codeAgentInput = $('input[name="agent[code]"]');
    let selectAgent = $('select[name="agent[id]"]');
    let idReceiptInput = $('input[name="id"]');
    let transactionDateReceiptInput = $('input[name="transaction_date"]');
    let taxInput = $('input[name="tax"]');
    let totalInput = $('input[name="total"]');
    let consumptionTaxInput = $('input[name="consumption_tax"]');
    let codeReceiptInput = $('input[name="code"]');
    let totalAmountInput = $('input[name="total_amount"]');
    let taxFractionRoundingCode =
        selectAgent.data("tax-fraction-rounding-code") ?? "four_down_five_up";
    let fractionRoundingCode =
        selectAgent.data("fraction-rounding-code") ?? "four_down_five_up";
    let taxTypeCode = "tax_included";
    let tax = 8;
    let productRemove = [];
    let SEARCH_PRODUCT_AGENT = "/product-agent/search-ajax";

    //Setup inputMask decimal
    let inputMaskDecimal = () => {
        let options = {
            greedy: false,
            alias: "decimal",
            radixPoint: ".",
            digits: 1,
            autoGroup: true,
            placeholder: " ",
        };
        Inputmask(options).mask($(".input-mark-decimal"));
    };

    //Setup inputMask Currency
    let inputMaskCurrency = () => {
        let options = {
            alias: "currency",
            prefix: "",
            digits: 0,
            autoGroup: true,
            placeholder: " ",
        };
        Inputmask(options).mask($(".input-mark-currency"));
    };

    //Submit post data receipt
    let handleSubmitDataReceipt = () => {
        $('[id^="form"]').on("submit", function (e) {
            e.preventDefault();
            if (!checkDataSubmit()) return false;
            let url = $(this).attr("action");
            let method = $(this).attr("method");
            let data = {
                id: idReceiptInput.val(),
                code: codeReceiptInput.val(),
                transaction_date: transactionDateReceiptInput.val(),
                tax: taxInput.val(),
                agent_id: selectAgent.val(),
                receipt_details: setupDataReceipt(),
                product_remove: productRemove,
            };
            return actionPostDataReceipt(url, method, data);
        });
    };

    //Setup data Receipt
    let setupDataReceipt = () => {
        let receiptDetails = [];
        $(".table-product tbody")
            .find("tr")
            .each(function () {
                let receiptDetail = {
                    id: $(this).data("receipt-detail-id"),
                    receipt_id: idReceiptInput.val(),
                    product_id:
                        $(this).find(".product-name").data("product-id") != ""
                            ? $(this).find(".product-name").data("product-id")
                            : 0,
                    name: $(this).find(".product-name").val(),
                    code: $(this).find(".product-code").val(),
                    quantity: $(this).find(".product-quantity").val(),
                    price: GLOBAL_CONFIG.replaceCommasPrice(
                        $(this).find(".product-price").val()
                    ),
                    unit_id: $(this).find(".unit-select").val(),
                    sort: $(this).data("sort"),
                };
                if (receiptDetail.code != "" || receiptDetail.name != "") {
                    receiptDetails.push(receiptDetail);
                }
            });
        return receiptDetails;
    };

    //Check product of receipt
    let checkDataSubmit = () => {
        $('button[type="submit"]').attr("disabled", false);
        if (setupDataReceipt().length === 0) {
            let content = "商品は必須項目です。";
            GLOBAL_CONFIG.sweetAlert("", content, true).then((result) => {
                if (result.isConfirmed) {
                    return $('button[type="submit"]').prop("disabled", false);
                }
            });
            return false;
        }
        return true;
    };

    //Action post data receipt
    let actionPostDataReceipt = (url, method, data) => {
        GLOBAL_CONFIG.loadingCustom(true);
        GLOBAL_CONFIG.callAjax(url, method, data)
            .done((response) => {
                GLOBAL_CONFIG.loadingCustom(false);
                window.location.href = response.url;
            })
            .fail((error) => {
                GLOBAL_CONFIG.loadingCustom(false);
                $('button[type="submit"]').attr("disabled", false);
                if (error.responseJSON.errors.code.length > 0) {
                    let html =
                        `<div class="alert alert-danger">
                        <ul class="mb-0">
                        <li>` +
                        error.responseJSON.errors.code[0] +
                        `</li>
                        </ul>
                    </div>`;
                    $("form").prepend(html);
                }
            });
    };

    //Calculate amount receipt by quantity of product
    let handleCalculatePriceTotalByQuantity = () => {
        $(document).on("keyup", ".product-quantity", function () {
            return calculateAmountProduct();
        });
    };

    //Calculate amount receipt by price of product
    let handleCalculatePriceTotalByPrice = () => {
        $(document).on("keyup", ".product-price", function () {
            return calculateAmountProduct();
        });
    };

    //Calculate amount receipt by tax of agent
    let handleCalculatePriceByTax = () => {
        taxInput.on("keyup", function () {
            return calculateAmountProduct();
        });
    };

    //Calculate amount receipt
    let calculateAmountProduct = () => {
        let totalAmountProduct = 0;
        let fractionRoundingCode = selectAgent.data('fraction-rounding-code');
        $(".table-product tbody")
            .find("tr")
            .each(function () {
                let quantity = $(this).find(".product-quantity").val();
                let price = GLOBAL_CONFIG.replaceCommasPrice(
                    $(this).find(".product-price").val()
                );

                if (quantity != "" && price != "") {
                    let amountProduct = roundingAmount(
                        quantity * price,
                        fractionRoundingCode
                    );
                    totalAmountProduct += Number(amountProduct);
                    $(this)
                        .find(".product-price-total")
                        .val(GLOBAL_CONFIG.formattedPrice(amountProduct));
                } else {
                    $(this).find(".product-price-total").val("");
                }
            });
        totalInput.val(totalAmountProduct.toLocaleString("ja-JA"));
        return calculateTax(totalAmountProduct);
    };

    //Calculate consumption tax
    let calculateTax = (totalAmountProduct) => {
        let taxFractionRoundingCode = selectAgent.data('tax-fraction-rounding-code');
        let tax = taxInput.val();
        console.log((totalAmountProduct * tax) / 100);
        let consumptionTax = roundingAmount(
            (totalAmountProduct * tax) / 100,
            taxFractionRoundingCode
        );
        consumptionTaxInput.val(GLOBAL_CONFIG.formattedPrice(consumptionTax));
        return calculateAmountReceipt(totalAmountProduct, consumptionTax);
    };

    //Calculate total amount receipt
    let calculateAmountReceipt = (totalAmountProduct, consumptionTax) => {
        let totalAmountReceipt =
            Number(totalAmountProduct) + Number(consumptionTax);
        return totalAmountInput.val(totalAmountReceipt.toLocaleString("ja-JA"));
    };

    //Set rounding
    let getTaxAndMethodAroundOfAgent = () => {
        selectAgent.on("select2:select", function (e) {
            let agent = e.params.data;
            taxFractionRoundingCode = agent.tax_fraction_rounding_code;
            fractionRoundingCode = agent.fraction_rounding_code;
            taxTypeCode = agent.tax_type_code;
            tax = agent.tax_rate ?? 8;
            taxInput.val(tax);
            $(this).attr(
                "data-tax-fraction-rounding-code",
                taxFractionRoundingCode
            );
            $(this).attr("data-fraction-rounding-code", fractionRoundingCode);
        });
    };

    //Rounding amount receipt
    let roundingAmount = (amount, rounding_type) => {
        switch (rounding_type) {
            case "truncation":
                return Math.floor(amount);
            case "rounding_up":
                let unit = String(amount).indexOf(".") === -1 ? 0 : 1;
                amount = amount ? Math.floor(amount) + unit : 0;
                return amount;

            default:
                return amount.toFixed(0);
        }
    };

    //Remove item product of receipt
    let removeItemProductOfReceipt = () => {
        $(document).on("click", ".btn-remove", function () {
            let tr = $(this).parents("tr");
            let receiptDetailId = tr.data("receipt-detail-id");
            if (receiptDetailId) {
                productRemove.push(receiptDetailId);
            }
            //Get toltip id aria-describedby="tooltip231400"
            let tooltipId = $(this).attr("aria-describedby");
            //remover tooltip
            $(document)
                .find("#" + tooltipId)
                .remove();
            //alert(tooltipId);
            tr.remove();
            $(".table-product tbody").append(htmlItemProductReceipt());
            GLOBAL_CONFIG.getNumerical();
            changeNameInputProducts();
            changeEnterIndex();
            inputMaskDecimal();
            inputMaskCurrency();
            //hide tooltip
            $(document)
                .find('[data-toggle="tooltip"]')
                .tooltip({ trigger: "hover" });
            return calculateAmountProduct();
        });
    };

    let changeNameInputProducts = () => {
        $(".table-product tbody")
            .find("tr")
            .each(function (i) {
                $(this).attr("data-sort", i);
                $(this)
                    .find(".product-code")
                    .attr("name", `products[` + i + `][code]`);
                $(this)
                    .find(".product-quantity")
                    .attr("name", `products[` + i + `][quantity]`);
                $(this)
                    .find(".product-price")
                    .attr("name", `products[` + i + `][price]`);
                $(this)
                    .find(".product-name")
                    .attr("name", `products[` + i + `][name]`);
                $(this)
                    .find(".unit-select")
                    .attr("name", `products[` + i + `][unit_code]`);
                $(this)
                    .find(".product-price-total")
                    .attr("name", `products[` + i + `][price_total]`);
                if (i > 19 && $(".table-product").data("table") === "receipt") {
                    $(this).remove();
                } else if (
                    i > 11 &&
                    $(".table-product").data("table") === "receipt-maruto"
                ) {
                    $(this).remove();
                }
            });
    };

    let changeEnterIndex = () => {
        $(document)
            .find("[enter-index]")
            .each(function (i) {
                $(this).attr("enter-index", i);
            });
    };
    //check line
    let checkLine = () => {
        let result = false;
        $(".table-product tbody")
            .find("tr:last-child")
            .each(function (i) {
                if ($(this).find(".product-name").val() != "") {
                    return (result = true);
                }
            });

        return result;
    };

    //Add item product of receipt
    let addItemProductOfReceipt = () => {
        $(document).on("click", ".btn-add-item", function (e) {
            if (!checkLine()) {
                let html = htmlItemProductReceipt();
                $(this).parents("tr").before(html);
                GLOBAL_CONFIG.getNumerical();
                changeNameInputProducts();
                inputMaskDecimal();
                inputMaskCurrency();
                $(document)
                    .find('[data-toggle="tooltip"]')
                    .tooltip({ trigger: "hover" });
                changeEnterIndex();
                return calculateAmountProduct();
            } else {
                let content =
                    "新しい行を追加したら、最下位行のデータが削除されます。よろしいですか.";
                GLOBAL_CONFIG.sweetAlert("", content, false).then((result) => {
                    if (result.isConfirmed) {
                        let html = htmlItemProductReceipt();
                        $(this).parents("tr").before(html);
                        GLOBAL_CONFIG.getNumerical();
                        changeNameInputProducts();
                        inputMaskDecimal();
                        inputMaskCurrency();
                        $(document)
                            .find('[data-toggle="tooltip"]')
                            .tooltip({ trigger: "hover" });
                        changeEnterIndex();
                        return calculateAmountProduct();
                    }
                });
            }
        });
    };

    let htmlItemProductReceipt = () => {
        let html = `<tr data-receipt-detail-id="">
        <td style="width: 5%;" class="vertical-middle text-center"><span class="stt">2</span></td>
        <td style="width: 15%;">
            <input name="products[1][code]" type="text" class="form-control product-code text-end" data-rule-maxlength="255" value="" autocomplete="off" >
        </td>
        <td style="width: 20%;">
            <input name="products[1][name]" type="text" class="form-control product-name text-end" data-product-id="" data-rule-maxlength="255" value="" autocomplete="off">
        </td>
        <td style="width: 10%;">
            <input name="products[1][quantity]" type="text" class="form-control product-quantity input-mark-decimal text-end" data-rule-maxlength="255" value="" inputmode="decimal" style="text-align: right;" autocomplete="off" >
        </td>
        <td>
            <select name="products[1][unit_code]" id="" class="form-select unit-select w-100" >
                                                <option value="1">CS</option>
                                                <option value="2">本</option>
                                                <option value="3">個</option>
                                                <option value="4">Kg</option>
                                                <option value="5">PK</option>
                                                <option value="6">束</option>
                                                <option value="7">袋</option>
                                                <option value="8">枚</option>
                                                <option value="9">丁</option>
                                                <option value="10">房</option>
                                                <option value="11">件</option>
                                            <option value="0" selected="selected" hidden="hidden" disabled="disabled">単位</option></select>
        </td>
        <td style="width: 13%;">
            <input name="products[1][price]" type="text" class="form-control input-mark-currency product-price text-end" data-rule-maxlength="255" value="" inputmode="numeric" style="text-align: right;" autocomplete="off" enter-index="14" fdprocessedid="bqtxhk">
        </td>
        <td style="width: 20%;" class="readonly-custom">
            <input name="products[1][price_total]" type="text" class="form-control product-price-total input-number-validate text-end" readonly="readonly" data-rule-maxlength="255" value="" autocomplete="off" fdprocessedid="jx5lg3">
        </td>
        <td style="width: 8%;" class="text-center vertical-middle p-0">
            <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-primary btn-add-item btn-circle me-1" data-toggle="tooltip" data-placement="top" fdprocessedid="xe7cld" title="追加クリア">
                    <svg class="svg-inline--fa fa-plus" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"></path></svg><!-- <i class="fa fa-plus"></i> Font Awesome fontawesome.com -->
                </button>
                <button type="button" class="btn btn-danger btn-remove btn-circle" data-toggle="tooltip" data-placement="top" fdprocessedid="cdoosd" title=" 行のクリア">
                    <svg class="svg-inline--fa fa-minus" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="minus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z"></path></svg><!-- <i class="fa fa-minus"></i> Font Awesome fontawesome.com -->
                </button>
            </div>
        </td>
    </tr>`;
        return html;
    };

    //Validate item product of receipt
    let validate = () => {
        $(document)
            .find(".product-price")
            .each(function (i) {
                $(this).rules("add", {
                    required: function () {
                        if (
                            $(document)
                                .find('input[name="products[' + i + '][name]"')
                                .val() != "" ||
                            $(document)
                                .find('input[name="products[' + i + '][code]"')
                                .val() != ""
                        ) {
                            return true;
                        } else {
                            return false;
                        }
                    },
                });
            });

        $(document)
            .find(".product-quantity")
            .each(function (i) {
                $(this).rules("add", {
                    required: function () {
                        if (
                            $(document)
                                .find('input[name="products[' + i + '][name]"')
                                .val() != "" ||
                            $(document)
                                .find('input[name="products[' + i + '][code]"')
                                .val() != ""
                        ) {
                            return true;
                        } else {
                            return false;
                        }
                    },
                });
            });
        $(document)
            .find(".unit-select")
            .each(function (i) {
                $(this).rules("add", {
                    required: function () {
                        if (
                            $(document)
                                .find('input[name="products[' + i + '][name]"')
                                .val() != "" ||
                            $(document)
                                .find('input[name="products[' + i + '][code]"')
                                .val() != ""
                        ) {
                            return true;
                        } else {
                            return false;
                        }
                    },
                });
            });

        $(document)
            .find(".product-name")
            .each(function (i) {
                $(this).rules("add", {
                    required: function () {
                        if (
                            $(document)
                                .find('input[name="products[' + i + '][code]"')
                                .val() != "" || $(document)
                                .find('input[name="products[' + i + '][name]"')
                                .val() != ""
                        ) {
                            return true;
                        } else {
                            return false;
                        }
                    },
                });
            });
    };

    //Search product by code of product
    let getDataProductByCode = () => {
        $(document).on("keydown", ".product-code", function (e) {
            $(document).find('.product-name').each(function () {
                $(this).parents('td').addClass('close-error');
            });
            if (e.keyCode === 13) {
                let tr = $(this).parents("tr");
                let data = {
                    unit_id: tr.find(".unit-select").val(),
                    product_code: $(this).val(),
                    agent_id: selectAgent.val(),
                };
                if (data.product_code == "" || data.product_code == null)
                    return false;

                setTimeout(function () {
                    let url = SEARCH_PRODUCT_AGENT;
                    GLOBAL_CONFIG.callAjax(url, "GET", data)
                        .done(function (response) {
                            let product = response.result;
                            if (product) {
                                tr.find(".product-name").val(product.name);
                                tr.find(".product-name").attr(
                                    "data-product-id",
                                    product.id
                                );
                                tr.find(".product-price").val(
                                    product.price
                                        ? GLOBAL_CONFIG.formattedPrice(
                                              product.price
                                          )
                                        : ""
                                );
                                if (product.unit_id) {
                                    UNIT.fillDataUnit(
                                        tr.find(".unit-select"),
                                        product.unit.name,
                                        product.unit.id
                                    );
                                }
                                calculateAmountProduct();
                            }
                        })
                        .fail(function (error) {
                            $('button[type="submit"]').prop("disabled", false);
                        });
                }, 500);
            }
        });
    };
    //Search product by code of product
    let getDataProductByUnit = () => {
        $(document).on("change", ".unit-select", function (e) {
            let tr = $(this).parents("tr");
            let data = {
                unit_id: $(this).val(),
                product_code: tr.find(".product-code").val(),
                agent_id: selectAgent.val(),
            };

            if (
                data.product_code == "" ||
                data.unit_id == null ||
                selectAgent.val() == null
            )
                return;

            setTimeout(function () {
                let url = SEARCH_PRODUCT_AGENT;
                GLOBAL_CONFIG.callAjax(url, "GET", data)
                    .done(function (response) {
                        let product = response.result;
                        if (product) {
                            tr.find(".product-name").val(product.name);
                            tr.find(".product-name").attr(
                                "data-product-id",
                                product.id
                            );
                            tr.find(".product-price").val(
                                product.price
                                    ? GLOBAL_CONFIG.formattedPrice(
                                          product.price
                                      )
                                    : ""
                            );
                            calculateAmountProduct();
                        }
                    })
                    .fail(function (error) {
                        $('button[type="submit"]').prop("disabled", false);
                    });
            }, 500);
        });
    };
    //Search product by code of product
    let getDataProductByName = () => {
        $(document).on("keydown", ".product-name", function (e) {
            if (e.keyCode === 13) {
                let tr = $(this).parents("tr");
                let data = {
                    unit_id: tr.find(".unit-select").val(),
                    unit_code: tr.find(".product-code").val(),
                    product_name: $(this).val(),
                    agent_id: selectAgent.val(),
                };

                if (data.product_name == "" || data.product_name == null)
                    return false;

                setTimeout(function () {
                    let url = SEARCH_PRODUCT_AGENT;
                    GLOBAL_CONFIG.callAjax(url, "GET", data)
                        .done(function (response) {
                            let product = response.result;
                            if (product) {
                                tr.find(".product-code").val(product.code);
                                tr.find(".product-name").attr(
                                    "data-product-id",
                                    product.id
                                );
                                tr.find(".product-price").val(
                                    product.price
                                        ? GLOBAL_CONFIG.formattedPrice(
                                              product.price
                                          )
                                        : ""
                                );
                                if (product.unit_id) {
                                    UNIT.fillDataUnit(
                                        tr.find(".unit-select"),
                                        product.unit.name,
                                        product.unit.id
                                    );
                                }
                                calculateAmountProduct();
                            }
                        })
                        .fail(function (error) {
                            $('button[type="submit"]').prop("disabled", false);
                        });
                }, 500);
            }
        });
    };

    //Action search product
    let actionGetProduct = (data, element) => {
        let url = SEARCH_PRODUCT_AGENT;
        data["agent_id"] = selectAgent.val();
        GLOBAL_CONFIG.callAjax(url, "GET", data)
            .done(function (response) {
                let product = response.result;
                if (product) {
                    element.find(".product-name").val(product.name);
                    element
                        .find(".product-name")
                        .attr("data-product-id", product.id);
                    element
                        .find(".product-price")
                        .val(
                            product.price
                                ? GLOBAL_CONFIG.formattedPrice(product.price)
                                : ""
                        );
                    if (product.unit_id) {
                        UNIT.fillDataUnit(
                            tr.find(".unit-select"),
                            product.unit.name,
                            product.unit.id
                        );
                    }
                    calculateAmountProduct();
                }
            })
            .fail(function (error) {
                $('button[type="submit"]').prop("disabled", false);
            });
    };

    //Focus receipt
    let focusoutReceipt = () => {
        codeAgentInput.focus();
        codeAgentInput.on("keydown", function (e) {
            let productCode = $(document).find(
                'input[name="products[0][code]"]'
            );

            setTimeout(() => {
                if (e.keyCode === 13 && $('.agent-select').val() != null) {
                    let offset = productCode.offset();
                    if (offset.top > 800) return;
                    $("html,body").animate({ scrollTop: offset.top - 150 }, 100);
                    productCode.focus();
                }
            }, 1000);
        });
    };

    let fixedButtonSubmit = () => {
        $(window).on("scroll", function () {
            var top = $(this).scrollTop();
            let topForm = $(".form-order").height() / 2.5;
            if (top <= topForm) {
                $(".grid-submit").addClass("fixed-submit");
            } else {
                $(".grid-submit").removeClass("fixed-submit");
            }
        });
    };

    return {
        _: function () {
            validate();
            focusoutReceipt();
            inputMaskDecimal();
            inputMaskCurrency();
            handleSubmitDataReceipt();
            removeItemProductOfReceipt();
            getTaxAndMethodAroundOfAgent();
            getDataProductByCode();
            getDataProductByName();
            getDataProductByUnit();
            calculateAmountProduct();
            handleCalculatePriceTotalByQuantity();
            handleCalculatePriceTotalByPrice();
            handleCalculatePriceByTax();
            fixedButtonSubmit();
            addItemProductOfReceipt();
        },
    };
})();

jQuery(function () {
    RECEIPT._();

    $('button[type="submit"]').on('click', function () {
        $(document).find('.product-name').each(function () {
            $(this).parents('td').removeClass('close-error');
        });
    });

    $(document).on('keydown', '.product-name', function () {
        $(this).parents('td').removeClass('close-error');
    });
});
