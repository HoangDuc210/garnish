var DEPOSIT = (function () {

    // Functions
    const formatDateTime = function (datetime, format) {
        format = format.replace(/yyyy/g, datetime.getFullYear());
        format = format.replace(/MM/g, ('0' + (datetime.getMonth() + 1)).slice(-2));
        format = format.replace(/M/g, datetime.getMonth() + 1);
        format = format.replace(/dd/g, ('0' + datetime.getDate()).slice(-2));
        format = format.replace(/d/g, datetime.getDate());
        format = format.replace(/HH/g, ('0' + datetime.getHours()).slice(-2));
        format = format.replace(/H/g, datetime.getHours());
        format = format.replace(/mm/g, ('0' + datetime.getMinutes()).slice(-2));
        format = format.replace(/m/g, datetime.getMinutes());
        format = format.replace(/ss/g, ('0' + datetime.getSeconds()).slice(-2));

        return format;
    };
    const makeYearMonthInfo = function (yearMonth) {
        if (!yearMonth) {
            return 'yyyy/MM/dd ~ yyyy/MM/dd';
        }

        var calculateDate = new Date(yearMonth);
        var firstDate = new Date(calculateDate.getFullYear(), calculateDate.getMonth(), 1);
        var lastDate = new Date(calculateDate.getFullYear(), calculateDate.getMonth() + 1, 0);

        return formatDateTime(firstDate, 'yyyy/MM/dd') + ' ~ ' + formatDateTime(lastDate, 'yyyy/MM/dd');
    };
    // Constants
    const trans = Page.trans;

    // Initialize
    const initializePaymentYearMonth = function () {
        const PaymentYearMonth = $("select[name='payment_year_month']");
        const PaymentYearMonthInfo = $(".payment-year-month-info");

        const initialize = function () {
            PaymentYearMonthInfo.html(makeYearMonthInfo(PaymentYearMonth.val()));
        };

        PaymentYearMonth.on("change", function () {
            initialize();
        });

        initialize();
    };
    const initializeBillingAgentInput = function () {
        var target = $("select[name='billing_agent_id']");
        var targetInput = $("input[name='input_billing_agent_id']");
        var selectedValue = target.attr("data-selectedValue");

        const request = GLOBAL_CONFIG.callAjax('/agents/billing-agents', "GET", {});
        request.done((response) => {
            var data = response.data;
            var blankOption = new Option(
                "",
                "",
                true,
            );
            blankOption.setAttribute('data-code', "");
            target.append(blankOption);

            for (let i = 0; i < data.length; i++) {
                let newOption = new Option(
                    data[i].name,
                    data[i].id,
                    false,
                    data[i].id === Number(selectedValue)
                );
                newOption.setAttribute('data-code', data[i].code);

                target.append(newOption);

                // search input
                if (data[i].id === Number(selectedValue)) {
                    targetInput.val(data[i].code);
                }
            }
        }).fail(function (error) {
            console.log(error);
        });

        target.on("change", function (e) {
            let option = e.target.querySelector(`option[value='${e.target.value}']`);
            targetInput.val(option.getAttribute('data-code'));
        });
        targetInput.on("input", function (e) {
            let option = target.find(`option[data-code='${e.target.value}']`);
            target.val(option.attr('value'));
        });
    };

    return {
        _: function () {
            initializePaymentYearMonth();
            initializeBillingAgentInput();
        },
    };
})();

jQuery(function () {
    DEPOSIT._();
});
