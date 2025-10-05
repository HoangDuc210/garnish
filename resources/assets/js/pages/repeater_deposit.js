var REPEATER_DEPOSIT = (function () {
    let paymentMonthInput = $(".payment-month");
    let totalAmountInput = $('input[name="total_amount"]');
    let receiptBalanceInput = $('input[name="receipt_balance"]');
    let paymentFirstInput = $('input[name="payment_first"]');
    let agentSelect = $('select[name="agent[id]"]');
    let repeaterDeposit;
    let url = "/deposits/search-ajax";
    let codeAgentInput = $('input[name="agent[code]"]');
    let typeCode = [
        {key: 'cash', value: '現金'},
        {key: 'check', value: '小切手'},
        {key: 'bank_transfer', value: '振込'},
        {key: 'commission', value: '手数料'},
        {key: 'hand_print', value: '手形'},
        {key: 'adjustment', value: '入金調整'},
    ];

    //Add deposit
    var addItemDeposit = function () {
        let repeater = $(".repeater").repeater({
            initEmpty: false,
            defaultValues: {
                "text-input": "foo",
            },
            show: function () {
                $(this).slideDown();
                setTimeout(function () {
                    GLOBAL_CONFIG.getNumerical();
                }, 10);

                selectedDayOfMonth();
                selectTypeCode();
                handleChangeSelectDay();
                handleChangeSelectTypeCode();
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
                setTimeout(function () {
                    calculateTotalReceiptAmount();
                }, 500);
            },
            ready: function () {
                setTimeout(function () {
                    selectedDayOfMonth();
                    selectTypeCode();
                }, 100);
            },
            isFirstItemUndeletable: false,
        });

        repeaterDeposit = repeater;
    };

    //Select day of the month
    let selectedDayOfMonth = () => {
        $(document).find(".select-day").each(function (i) {
            $(this).select2({
                placeholder: "日",
                language: "ja",
                theme: "bootstrap4",
                allowClear: false,
                minimumResultsForSearch: -1,
            });
        });
    };

    //Select type code of deposit
    let selectTypeCode = () => {
        $(document).find(".deposit-currency").each(function (i) {
            $(this).select2({
                placeholder: "種別",
                language: "ja",
                theme: "bootstrap4",
                allowClear: false,
                minimumResultsForSearch: -1,
            });
        });
    }

    //Setup date
    let setupDate = function () {
        let dateMouth = paymentMonthInput.val();
        let lastDayInMonth = moment(dateMouth).endOf("month").format("DD");
        let dataDay = [];
        for (let i = 1; i <= lastDayInMonth; i++) {
            let data = {
                id: i,
                text: i,
            };
            dataDay.push(data);
        }

        return dataDay;
    };

    var handleChangeSelectDay = function () {
        $(document)
            .find(".select-day")
            .each(function () {
                $(this).on("select2:opening", function (e) {
                    $(this).empty().trigger("change");
                    let data = setupDate();
                    for (let i = 1; i <= data.length; i++) {
                        $(this).append(
                            "<option value=" + i + ">" + i + "</option>"
                        );
                    }
                });
            });
    };

    //Handle Select type code of deposit
    var handleChangeSelectTypeCode = function () {
        $(document)
            .find(".deposit-currency")
            .each(function () {
                $(this).on("select2:opening", function (e) {
                    let depositCurrency = $(this);
                    $(this).empty().trigger("change");

                    typeCode.map(function (item){
                        depositCurrency.append(
                            "<option value=" + item.key + ">" + item.value + "</option>"
                        );
                    });
                });
            });
    };

    //Handle calculate total amount of deposit
    var handleCalculateTotalAmountDeposit = function () {
        $(document).on("keyup", ".amount-input", function () {
            return calculateTotalReceiptAmount();
        });
    };

    //Calculate total amount of deposit
    let calculateTotalReceiptAmount = () => {
        let totalAmount = 0;
        $(document)
            .find(".amount-input")
            .each(function () {
                let amount = $(this).val()
                    ? GLOBAL_CONFIG.replaceCommasPrice($(this).val())
                    : 0;

                totalAmount += parseFloat(amount);
            });

        totalAmountInput.val(totalAmount.toLocaleString("ja-JA"));
        return calculateReceiptBalance(totalAmount);
    };

    //calculate receipt balance
    let calculateReceiptBalance = (totalAmount) => {
        let receiptBalanceAmount = 0;
        let amountPaymentFirst = paymentFirstInput.val();

        receiptBalanceAmount =
            Number(GLOBAL_CONFIG.replaceCommasPrice(amountPaymentFirst)) + Number(totalAmount);
        return receiptBalanceInput.val(
            receiptBalanceAmount.toLocaleString("ja-JA")
        );
    };

    //Get payment amount first deposit
    var handleGetPaymentFirst = function () {
        agentSelect.on("select2:select", function (e) {
            let agent = e.params.data;
            let paymentMonth = paymentMonthInput.val();
            let url = "get-deposit-amount";
            let data = {
                agent_id: agent.id,
                payment_date: moment(paymentMonth)
                    .format("YYYY-MM-DD"),
            };

            setTimeout(function () {
                GLOBAL_CONFIG.callAjax(url, "Post", data)
                    .done(function (response) {
                        let billing = response.result;

                        return paymentFirstInput.val(
                            billing.toLocaleString("ja-JA")
                        );
                    })
                    .fail(function (error) {
                    });
            }, 100);
        });
    };

    //Get data deposit by month
    var handleGetDataDepositByMouth = function () {
        paymentMonthInput.on("change", function () {
            if (!agentSelect.val()) {
                return false;
            }

            let data = {
                agent_id: agentSelect.val(),
                payment_date: moment($(this).val()).format("YYYY-MM"),
            };

            return getDataDeposit(url, data);
        });
    };

    //Get data payment_first by mouth
    var handleGetPaymentFirstDepositByMouth = function () {
        paymentMonthInput.on("change", function () {
            if (!agentSelect.val()) {
                return false;
            }

            let data = {
                agent_id: agentSelect.val(),
                payment_date: moment($(this).val()).format("YYYY-MM"),
            };

            let url = "get-deposit-amount";

            setTimeout(function () {
                GLOBAL_CONFIG.callAjax(url, "Post", data)
                    .done(function (response) {
                        let billing = response.result;

                        return paymentFirstInput.val(
                            billing.toLocaleString("ja-JA")
                        );
                    })
                    .fail(function (error) {
                    });
            }, 100);

        });
    };

    //Get data deposit by code of agent
    var handleGetDataDepositByCodeAgent = function () {
        codeAgentInput.on("keyup", function () {
            let data = {
                agent_code: $(this).val(),
                payment_date: moment(paymentMonthInput.val()).format("YYYY-MM"),
            };
            return getDataDeposit(url, data);
        });
    };

    //Get data deposit by id of agent
    var handleGetDataDepositByIdAgent = function () {
        agentSelect.on("select2:select", function (e) {
            let agent = e.params.data;
            let data = {
                agent_id: agent.id,
                payment_date: moment(paymentMonthInput.val()).format("YYYY-MM"),
            };
            return getDataDeposit(url, data);
        });
    }

    let getDataDeposit = function (url, data) {
        setTimeout(function () {
            GLOBAL_CONFIG.callAjax(url, "GET", data)
                .done(function (response) {
                    let deposits = response.results.data;
                    if (deposits.length > 0) {
                        return setDataRepeaterDeposit(deposits);
                    }else{
                        return setDataEmpty();
                    }
                })
                .fail(function (error) {
                    return setDataEmpty();
                });
        }, 500);
    };

    let setDataRepeaterDeposit = function (deposits) {
        let dataDeposits = [];

        deposits.map(function (deposit) {
            let data = {
                amount: deposit.amount.toLocaleString("ja-JA"),
                type_code: typeCode.find(i => i.key === deposit.type_code),
                payment_date: Number(moment(deposit.payment_date).format("DD")),
                memo: deposit.memo,
            };

            dataDeposits.push(data);
        });
        repeaterDeposit.setList(dataDeposits);
        $(document)
            .find(".select-day")
            .each(function (i) {
                let day = dataDeposits[i].payment_date;
                let newOption = new Option(day, day, true, true);
                $(this).append(newOption).trigger("change");

                $(this).attr("disabled", "disabled");
            });

        $(document)
            .find(".deposit-currency")
            .each(function (i) {
                let key = dataDeposits[i].type_code.key;
                let value = dataDeposits[i].type_code.value;
                let newOption = new Option(value, key, true, true);
                $(this).append(newOption).trigger("change");

                $(this).attr("disabled", "disabled");
            });

        $(document)
            .find(".amount-input")
            .each(function (i) {
                $(this).attr("disabled", "disabled");
            });

        $(document)
            .find(".memo")
            .each(function (i) {
                $(this).attr("disabled", "disabled");
            });

        $(".table-deposit")
            .find("tbody tr")
            .each(function (i) {
                $(this).find(".btn-remove").remove();
            });

        setTimeout(function () {
            calculateReceiptBalance();
            calculateTotalReceiptAmount();
        }, 100);
    };

    let setDataEmpty = function () {
        repeaterDeposit.setList({ 'text-input': 'set-foo' });
        setTimeout(function () {
            calculateReceiptBalance();
            calculateTotalReceiptAmount();
        }, 100);
    }

    let handleCalculateReceiptBalance = function () {
        $('#btn-choose-agent').on('click', function () {
            setTimeout(function () {
                let paymentDate =  moment($('.payment-month').val()).format("YYYY-MM-DD");
                let agentId = $('.agent-select').val();
                let url = "get-deposit-amount";
                let data = {
                    payment_date: paymentDate,
                    agent_id: agentId
                }

                GLOBAL_CONFIG.callAjax(url, "Post", data)
                    .done(function (response) {
                        let billing = response.result;

                        paymentFirstInput.val(
                            billing.toLocaleString("ja-JA")
                        );

                        return calculateTotalReceiptAmount();
                    })
                    .fail(function (error) {
                    });
            }, 500);
        });
    }
    return {
        _: function () {
            addItemDeposit();
            handleCalculateTotalAmountDeposit();
            handleGetPaymentFirst();
            handleGetDataDepositByMouth();
            handleGetDataDepositByCodeAgent();
            handleChangeSelectDay();
            handleChangeSelectTypeCode();
            handleGetDataDepositByIdAgent();
            handleGetPaymentFirstDepositByMouth();
            handleCalculateReceiptBalance();
        },
    };
})();

jQuery(function () {
    REPEATER_DEPOSIT._();
});
