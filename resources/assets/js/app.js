window.GLOBAL_CONFIG = (function () {
    let URL_SEARCH_AGENT_AJAX = "/agents/search-ajax";
    let agentCodeInput = $(".agent-code");
    let agentTelInput = $(".agent-tel");
    let agentFaxInput = $(".agent-fax");
    let agentAddressInput = $(".agent-address");
    let agentZipCodeInput = $(".agent-post_code");
    let agentBillCycleInput = $(".agent-closing_date");
    let agentSelect = $('select[name="agent[id]"]');
    let agentNameInput = $(".agent-name");

    const initializeEnterIndexForm = () => {
        // overide enter key down event
        window.addEventListener(
            "keydown",
            (e) => {
                if (
                    e.keyIdentifier == "U+000A" ||
                    e.keyIdentifier == "Enter" ||
                    e.key === "Enter"
                ) {
                    if (
                        e.target.nodeName == "INPUT" ||
                        e.target.nodeName == "SELECT" ||
                        e.target.classList.contains("select2-selection") // for jquery select2
                    ) {
                        e.preventDefault();

                        const inputs = [];
                        const form =
                            document.querySelector(".enter-index-form");
                        if (!form) return;
                        const formInputs = form.querySelectorAll(
                            `input:not(input[type='hidden']), select`
                        );

                        // add tab index
                        formInputs.forEach((x, index) => {
                            // x.setAttribute('enter-index', index);
                            if (!x.readOnly) {
                                inputs.push(x);
                            }
                        });

                        inputs.forEach((x, index) => {
                            x.setAttribute("enter-index", index);
                        });

                        var nextTabIndex =
                            Number(e.target.getAttribute("enter-index")) + 1;
                        var nextFormInput = inputs[nextTabIndex];

                        if (nextFormInput) {
                            nextFormInput.focus();
                        } else if (inputs[0]) {
                            inputs[0].focus();
                        }

                        return false;
                    }
                }
            },
            true
        );
    };
    return {
        toggleBuilder: () => {
            $('[data-init-plugin="select2"]').each(function () {
                let isDisableSearch = $(this).attr("data-disable-search");
                let config = {
                    theme: "bootstrap4",
                    minimumResultsForSearch:
                        !isDisableSearch || isDisableSearch == "true" ? -1 : 1,
                    disabled: !!$(this).attr("readonly"),
                    language: "ja",
                };

                $(this).select2(config);
            });

            $("[toggle-confirm-delete]").on("click", function (e) {
                e.preventDefault();
                let href = $(this).data("href");
                let content = $(this).data("content");
                GLOBAL_CONFIG.sweetAlert("", content).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });

            /**
             * Bootstrap date-picker
             */
            $("[data-toggle=date]").bootstrapDP({
                language: "ja",
                ampm: true,
                autoclose: true,
            });

            $("[data-toggle=month]").bootstrapDP({
                format: "yyyy/mm",
                viewMode: "months",
                minViewMode: "months",
                language: "ja",
                autoclose: true,
            });
            /**
             * Jquery Tooltip
             */
            setTimeout(function () {
                $(document)
                    .find('[data-toggle="tooltip"]')
                    .tooltip({ trigger: "hover" });
            }, 500);

            $(document).on('click', '[data-toggle="tooltip"]', function() {
                $(document).find('.bs-tooltip-auto').remove();
            });
        },
        callAjax: (url = "", type = "", data = {}) => {
            let options = {
                url: url,
                type: type,
                dataType: "json",
                data: data,
            };
            return $.ajax(options);
        },
        selectAgent: () => {
            $(".agent-select").select2({
                placeholder: "得意先は必ずご入力ください。",
                language: "ja",
                theme: "bootstrap4",
                allowClear: false,
                ajax: {
                    url: "/agents/search-ajax",
                    dataType: "json",
                    type: "GET",
                    delay: 250,
                    closeOnSelect: true,
                    data: function (params) {
                        return {
                            name: params.term,
                        };
                    },
                    processResults: function (data) {
                        if (agentSelect.data("parent") == "parent") {
                            var res = data.results.map(function (item) {
                                if (
                                    item != null &&
                                    item.id == item.billing_agent_id
                                ) {
                                    return {
                                        id: item.id,
                                        code: item.code,
                                        text: item.name,
                                    };
                                }
                            });
                            res = res.filter(function (item) {
                                return item !== undefined;
                            });
                        } else {
                            var res = data.results.map(function (item) {
                                return {
                                    id: item.id,
                                    code: item.code,
                                    text: item.name,
                                    post_code: item.post_code,
                                    tel: item.tel,
                                    fax: item.fax,
                                    address: item.address,
                                    closing_date: item.closing_date,
                                    tax_fraction_rounding_code:
                                        item.tax_fraction_rounding_code,
                                    fraction_rounding_code:
                                        item.fraction_rounding_code,
                                    tax_type_code: item.tax_type_code,
                                    tax_rate: item.tax_rate,
                                };
                            });
                        }
                        return {
                            results: res,
                        };
                    },
                },
            });
        },
        selectedAgent: () => {
            let agentSelect = $('select[name="agent[id]"]');
            let nameAgent = agentSelect.data("agent-name");
            let idAgent = agentSelect.data("agent-id");
            let selectData = agentSelect.data("agent-selected");
            if (nameAgent && idAgent) {
                let newOption = new Option(nameAgent, idAgent, true, true);
                agentSelect.append(newOption).trigger("change");
            }
            if (selectData > 0) {
                GLOBAL_CONFIG.callAjax(URL_SEARCH_AGENT_AJAX, "GET", {
                    id: selectData,
                }).done((data) => {
                    let agent = data.results;
                    let newOption = new Option(
                        agent[0].name,
                        agent[0].id,
                        true,
                        true
                    );
                    agentSelect.append(newOption).trigger("change");
                });
            }
        },
        fillDataGentSelected: () => {
            agentSelect.on("select2:select", function (e) {
                let agent = e.params.data;
                window.agent = agent;
                agentNameInput.val(agent.text);
                agentCodeInput.val(agent.code);
                agentZipCodeInput.val(agent.post_code);
                agentTelInput.val(agent.tel);
                agentFaxInput.val(agent.fax);
                agentAddressInput.val(agent.address);
                agentBillCycleInput.val(agent.closing_date);
            });
        },
        selectAgentByCode: function () {
            let agentCodeInput = $(".agent-code");
            let agentSelect = $('select[name="agent[id]"]');

            agentCodeInput.on("keydown", function (e) {
                let code = $(this).val();
                let data = {
                    code: String(code).trim(),
                };

                if ((data.code == "" || data.code == undefined || data.code == null) || e.keyCode == 32) return;
                
                if (e.keyCode == 13) {
                    GLOBAL_CONFIG.callAjax(URL_SEARCH_AGENT_AJAX, "GET", data).done(
                        (data) => {
                            let agent = data.results;
    
                            if (agent.length === 0) {
                                $(".agent-select").val("").trigger("change");
                                agentZipCodeInput.val("");
                                agentTelInput.val("");
                                agentFaxInput.val("");
                                agentAddressInput.val("");
                                agentBillCycleInput.val("");
                                $("input[name='tax']").val(8);
                            } else {
                                agent.map((element) => {
                                    agentZipCodeInput.val(element.post_code);
                                    agentTelInput.val(element.tel);
                                    agentFaxInput.val(element.fax);
                                    agentAddressInput.val(element.address);
                                    agentBillCycleInput.val(element.closing_date);
                                    let newOption = new Option(
                                        element.name,
                                        element.id,
                                        true,
                                        true
                                    );
                                    agentSelect.append(newOption).trigger("change");
                                    $("input[name='tax']").val(
                                        element.tax_rate ?? 8
                                    );
                                    agentSelect.attr('data-tax-fraction-rounding-code', element.tax_fraction_rounding_code);
                                    agentSelect.attr('data-fraction-rounding-code', element.fraction_rounding_code);
                                });
                                let productCode = $(document).find(
                                    'input[name="products[0][code]"]'
                                );

                                setTimeout(() => {
                                    productCode.focus();
                                }, 500);
                            }
                        }
                    );
                }
            });
        },
        unavailableKeyCode: () => {
            let number =
                (!event.shiftKey &&
                    !(event.keyCode < 48 || event.keyCode > 57)) ||
                !(event.keyCode < 96 || event.keyCode > 105) ||
                event.keyCode == 46 ||
                event.keyCode == 8 ||
                !event.keyCode == 190 ||
                event.keyCode == 188 ||
                !event.keyCode == 13 ||
                (event.keyCode >= 35 && event.keyCode <= 39);

            return number;
        },
        validateInputNumber: () => {
            $(document).on(
                "keydown",
                ".input-number-validate",
                function (event) {
                    if (
                        !GLOBAL_CONFIG.unavailableKeyCode() ||
                        event.keyCode == 188
                    ) {
                        event.preventDefault();
                    }
                }
            );

            $(document).on("keydown", ".input-mark-currency", function (event) {
                if (event.keyCode == 189) {
                    event.preventDefault();
                }
            });

            $(document).on("keydown", ".input-mark-decimal", function (event) {
                if (event.keyCode == 189) {
                    event.preventDefault();
                }
            });
        },
        getNumerical: () => {
            let numerical = $(".table").find(".stt");
            numerical.each(function (i, v) {
                return $(this).text(i + 1);
            });
        },
        sweetAlert: (title = "", content = "", alert = false) => {
            return Swal.fire({
                title: title,
                text: content,
                icon: "warning",
                showCancelButton: alert ? false : true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "はい（Y）",
                cancelButtonText: "いいえ（N）",
            });
        },
        addRequiredInput: () => {
            var formGroup = $(".form-group");

            formGroup.each(function () {
                if ($(this).attr("required")) {
                    let colFormLabel = $(this).find(".col-form-label");

                    let html = '<span class="text-danger">*';

                    colFormLabel.append(html);
                }
            });
        },
        customAlertTheme: () => {
            let alert = $(".alert.alert-success");
            if (alert.length > 0) {
                alert.addClass(
                    "fade show d-flex align-items-center justify-content-between"
                );
                alert.attr("data-bs-dismiss", "alert");
                setTimeout(() => {
                    alert.remove();
                }, 3000);
            }
        },
        formattedPrice: (value) => {
            return String(value)
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        makeMoney: () => {
            $(document).on("keyup", ".input-make-money", function (event) {
                $(this).val(function (index, value) {
                    return GLOBAL_CONFIG.formattedPrice(value);
                });
            });
        },
        replaceCommasPrice: (value) => {
            return String(value)
                .replace(",", "")
                .replace(",", "")
                .replace(",", "");
        },
        loadingCustom: (close = false, alert = {}) => {
            let body = $("body");
            let html = '<div class="custom overlay-spinner"></div>';
            if (close) {
                body.addClass("overflow-hidden");
                body.append(html);
            } else {
                body.find(".overlay-spinner").remove();
                body.removeClass("overflow-hidden");
            }

            if (alert.length > 0) {
                GLOBAL_CONFIG.sweetAlert("", alert.content, true);
            }
        },
        addSelectLimit: () => {
            let html = $(".select-limit").html();
            $(".box-limit").each(function (i) {
                if (i === 1) {
                    $(this).append(html);
                    $(this).addClass("mt-2");
                }
            });
            $(".select-limit").remove();
        },
        addAutocompleteInput: () => {
            $("input").attr("autocomplete", "off");
            $("form").attr("autocomplete", "off");
        },
        initializeEnterIndexForm: () => {
            initializeEnterIndexForm();
        },
        init: function () {
            this.toggleBuilder();
            this.selectAgent();
            this.selectedAgent();
            this.validateInputNumber();
            this.getNumerical();
            this.fillDataGentSelected();
            this.selectAgentByCode();
            this.addRequiredInput();
            this.customAlertTheme();
            this.makeMoney();
            this.addSelectLimit();
            this.addAutocompleteInput();
            this.initializeEnterIndexForm();
        },
    };
})();

$(document).ready(function () {
    GLOBAL_CONFIG.init();
});
