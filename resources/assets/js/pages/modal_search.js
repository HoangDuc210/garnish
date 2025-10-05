let MODAL_SEARCH_AGENT = (function () {
    let name_search = "";
    let code_search = "";
    let address_search = "";
    let radio = 0;
    let page = 0;
    //Get data agent
    var modalSearchAgent = () => {
        $('[data-bs-target="#modalSearchAgent"]').on("click", function (e) {
            let url = $(this).data("url-search");
            let data = {
                name: name_search,
                code: code_search,
                address: address_search,
                page: page,
            }
            actionGetDataAgent(url, data);
        });
    };

    //Pagination
    let pagination = (response) => {
        $("#modalSearchAgent").find(".pagination").remove();
        if (response.results.data.length === 0) return;
        let links = response.results.links;
        links.shift();
        links.pop();
        //Append ul
        let html = `
                <ul class="pagination float-start mb-0">
                </ul>
            `;
        $(document).find("#modalSearchAgent .modal-footer").prepend(html);

        // Prev page
        let prevLink = `${
            response.results.prev_page_url
                ? `<li class="page-item">
                    <a class="page-link" href="${response.results.prev_page_url}">
                        <i class="fa-regular fa-circle-left fa-2x"></i>
                    </a>
                </li>`
                : `<li class="page-item disabled">
                    <span class="page-link">
                        <i class="fa-regular fa-circle-left fa-2x"></i>
                    </span>
                </li>`
        }`;
        $(document).find("#modalSearchAgent .pagination").append(prevLink);

        //Append page
        links.map(function (link) {
            let html = `
                    <li class="page-item ${link.active ? "active" : ""} ${
                link.url ? "" : "disabled"
            }">
                        ${
                            !link.active
                                ? `<a href="${link.url}" class="page-link">${link.label}</a>`
                                : `<span class="page-link">${link.label}</span>`
                        }
                    </li>
                `;
            $(document).find("#modalSearchAgent .pagination").append(html);
        });

        // Next page
        let nextLink = `${
            response.results.next_page_url
                ? `<li class="page-item">
                    <a class="page-link" href="${response.results.next_page_url}">
                        <i class="fa-regular fa-circle-right fa-2x"></i>
                    </a>
                </li>`
                : `<li class="page-item disabled">
                    <span class="page-link">
                        <i class="fa-regular fa-circle-right fa-2x"></i>
                    </span>
                </li>`
        }`;
        $(document).find("#modalSearchAgent .pagination").append(nextLink);
    };

    //Action get data agent
    let actionGetDataAgent = (url, data = {}) => {
        $("#modalSearchAgent .modal-body").addClass(
            "overlay-spinner overflow-hidden"
        );
        setTimeout(() => {
            GLOBAL_CONFIG.callAjax(url, "GET", data)
                .done(function (response) {
                    let agents = response.results.data;
                    $("#data-table-search-agent tbody").html("");
                    $("#modalSearchAgent .modal-body").removeClass(
                        "modal-overflow"
                    );
                    if (agents.length > 0) {
                        $("#modalSearchAgent .modal-body").addClass(
                            "modal-overflow"
                        );
                        agents.map(function (agent) {
                            let html = `
                                <tr>
                                    <td class="text-center" style="width: 5%;">
                                        <input type="radio" name="agent_id" class="form-check-input"
                                            data-id="${agent.id}"
                                            data-name="${agent.name}"
                                            data-code="${agent.code}"
                                            data-post_code="${agent.post_code}"
                                            data-address="${agent.address}"
                                            data-tel="${agent.tel}"
                                            data-fax="${agent.fax}"
                                            data-closing_date="${agent.closing_date}"
                                            value="${agent.id}"
                                            ${agent.id == radio ? 'checked' : null}
                                        >
                                    </td>
                                    <td>${agent.code}</td>
                                    <td>${agent.name}</td>
                                    <td>${agent.post_code ?? ""}</td>
                                    <td>${agent.address ?? ""}</td>
                                    <td>${agent.address_more ?? ""}</td>
                                    <td>${agent.tel ?? ""}</td>
                                    <td>${agent.fax ?? ""}</td>
                                </tr>
                            `;
                            $("#data-table-search-agent tbody").append(html);
                        });
                    } else {
                        let html = `
                        <tr>
                            <td colspan="8" class="text-center">データなし</td>
                        </tr>
                    `;
                        $("#data-table-search-agent tbody").html(html);
                        $("#modalSearchAgent .modal-body").removeClass(
                            "modal-overflow"
                        );
                    }
                    pagination(response);
                    $("#modalSearchAgent .modal-body").removeClass(
                        "overlay-spinner overflow-hidden"
                    );
                })
                .fail(function (error) {
                    $("#modalSearchAgent .modal-body").addClass(
                        "overlay-spinner overflow-hidden"
                    );
                });
        }, 1000);
    };

    //Get data of page
    let getDataOfPageAgent = () => {
        $(document).on(
            "click",
            "#modalSearchAgent .pagination li a",
            function (e) {
                e.preventDefault();
                let url = $(this).attr("href");
                let data = {
                    code: code_search,
                    name: name_search,
                    address: address_search,
                };
                page = $(this).text();
                actionGetDataAgent(url, data);
            }
        );
    };

    //Choose data agent
    let chooseDataAgent = () => {
        $("#btn-choose-agent").on("click", function (e) {
            e.preventDefault();
            let dataAgentChecked = {}
            $("#data-table-search-agent")
                .find('input[name="agent_id"]')
                .each(function () {
                    if ($(this).prop("checked")) {
                        dataAgentChecked['id'] = $(this).data("id");
                        dataAgentChecked['name'] = $(this).data("name");
                        dataAgentChecked['code'] = $(this).data("code");
                        dataAgentChecked['post_code'] = $(this).data("post_code");
                        dataAgentChecked['tel'] = $(this).data("tel");
                        dataAgentChecked['fax'] = $(this).data("fax");
                        dataAgentChecked['closing_date'] = $(this).data('closing_date');
                        dataAgentChecked['address'] = $(this).data("address");

                        radio = $(this).val();
                    }
                });

            if (dataAgentChecked) {
                $(".agent-name").val(dataAgentChecked.name);
                $(".agent-code").val(dataAgentChecked.code);
                $(".agent-tel").val(dataAgentChecked.tel);
                $(".agent-fax").val(dataAgentChecked.fax);
                $(".agent-post_code").val(dataAgentChecked.post_code);
                $(".agent-closing_date").val(dataAgentChecked.closing_date);
                $(".agent-address").val(dataAgentChecked.address);

                $(".agent-select").empty().trigger('change');
                var newOption = new Option(dataAgentChecked.name, dataAgentChecked.id, false, false);
                $(".agent-select").append(newOption).trigger('change');
            }
        });
    };

    //Search modal data
    let searchModalDataAgent = () => {
        $(".form-search-modal-agent").on("submit", function (e) {
            e.preventDefault();
            code_search = $(this).find('input[name="code"]').val();
            name_search = $(this).find('input[name="name"]').val();
            address_search = $(this).find('input[name="parent"]').val();

            let url = $(this).attr("action");
            let data = {
                code: code_search,
                name: name_search,
                parent: address_search,
            };

            actionGetDataAgent(url, data);

            $('button[type="submit"]').prop("disabled", false);
        });
    };

    //Clear all
    let clearAll = () => {
        name_search  = '';
        code_search = '';
        address_search = '';
        radio = 0;
        page = 0;
        $(document).find("#data-table-search-agent tbody tr").remove();
        $(document).find("#modalSearchAgent .pagination").remove();
        $(document)
            .find("#modalSearchAgent .modal-body")
            .removeClass("modal-overflow");
        $(".form-search-modal-agent").find('input[name="code"]').val("");
        $(".form-search-modal-agent").find('input[name="name"]').val("");
        $(".form-search-modal-agent").find('input[name="parent"]').val("");

        $('#clearAgent').on('click', function() {
            name_search  = '';
            code_search = '';
            address_search = '';
            radio = 0;
            page = 0;
            $(document).find("#data-table-search-agent tbody tr").remove();
            $(document).find("#modalSearchAgent .pagination").remove();
            $(document)
                .find("#modalSearchAgent .modal-body")
                .removeClass("modal-overflow");
            $(".form-search-modal-agent").find('input[name="code"]').val("");
            $(".form-search-modal-agent").find('input[name="name"]').val("");
            $(".form-search-modal-agent").find('input[name="parent"]').val("");
        });
    }
    return {
        _: function () {
            modalSearchAgent();
            getDataOfPageAgent();
            chooseDataAgent();
            searchModalDataAgent();
            clearAll();
        },
    };
})();

let MODAL_SEARCH_PRODUCT = (function () {
    let name_search = "";
    let code_search = "";
    let radio = 0;
    let page = 0;
    //Get data agent
    var modalSearchProduct = () => {
        $('[data-bs-target="#modalSearchProduct"]').on("click", function (e) {
            let url = $(this).data("url-search");
            let data = {
                name: name_search,
                code: code_search,
                page: page,
            }
            actionGetDataProduct(url, data);
        });
    };

    //Pagination
    let pagination = (response) => {
        $("#modalSearchProduct").find(".pagination").remove();
        if (response.results.data.length === 0) return;
        let links = response.results.links;
        links.shift();
        links.pop();
        //Append ul
        let html = `
                <ul class="pagination float-start mb-0">
                </ul>
            `;
        $(document).find("#modalSearchProduct .modal-footer").prepend(html);

        // Prev page
        let prevLink = `${
            response.results.prev_page_url
                ? `<li class="page-item">
                    <a class="page-link" href="${response.results.prev_page_url}">
                        <i class="fa-regular fa-circle-left fa-2x"></i>
                    </a>
                </li>`
                : `<li class="page-item disabled">
                    <span class="page-link">
                        <i class="fa-regular fa-circle-left fa-2x"></i>
                    </span>
                </li>`
        }`;
        $(document).find("#modalSearchProduct .pagination").append(prevLink);

        //Append page
        links.map(function (link) {
            let html = `
                    <li class="page-item ${link.active ? "active" : ""} ${
                link.url ? "" : "disabled"
            }">
                        ${
                            !link.active
                                ? `<a href="${link.url}" class="page-link">${link.label}</a>`
                                : `<span class="page-link">${link.label}</span>`
                        }
                    </li>
                `;
            $(document).find("#modalSearchProduct .pagination").append(html);
        });

        // Next page
        let nextLink = `${
            response.results.next_page_url
                ? `<li class="page-item">
                    <a class="page-link" href="${response.results.next_page_url}">
                        <i class="fa-regular fa-circle-right fa-2x"></i>
                    </a>
                </li>`
                : `<li class="page-item disabled">
                    <span class="page-link">
                        <i class="fa-regular fa-circle-right fa-2x"></i>
                    </span>
                </li>`
        }`;
        $(document).find("#modalSearchProduct .pagination").append(nextLink);
    };

    //Action get data agent
    let actionGetDataProduct = (url, data = {}) => {
        $("#modalSearchProduct .modal-body").addClass(
            "overlay-spinner overflow-hidden"
        );
        setTimeout(() => {
            GLOBAL_CONFIG.callAjax(url, "GET", data)
                .done(function (response) {
                    let products = response.results.data;
                    $("#data-table-search-product tbody").html("");
                    $("#modalSearchProduct .modal-body").removeClass(
                        "modal-overflow"
                    );
                    if (products.length > 0) {
                        $("#modalSearchProduct .modal-body").addClass(
                            "modal-overflow"
                        );
                        products.map(function (product) {
                            let html = `
                                <tr>
                                    <td class="text-center" style="width: 5%;">
                                        <input type="radio" name="product_id" class="form-check-input"
                                            data-code="${product.code}"
                                            data-name="${product.name}"
                                            value="${product.id}"
                                            ${product.id == radio ? 'checked' : null}
                                            >
                                    </td>
                                    <td class="text-center">${product.code}</td>
                                    <td>${product.name}</td>
                                    <td class="text-center">${
                                        !product.unit_id || product.unit_id == null
                                            ? ""
                                            : product.unit.name
                                    }</td>
                                    <td class="text-center">${
                                        product.quantity == null
                                            ? "0"
                                            : product.quantity
                                    }</td>
                                    <td class="text-end">${
                                        product.price == null
                                            ? "0"
                                            : product.price
                                    }</td>
                                </tr>
                            `;
                            $("#data-table-search-product tbody").append(html);
                        });
                    } else {
                        let html = `
                        <tr>
                            <td colspan="8" class="text-center">データなし</td>
                        </tr>
                    `;
                        $("#data-table-search-product tbody").html(html);
                        $("#modalSearchProduct .modal-body").removeClass(
                            "modal-overflow"
                        );
                    }
                    pagination(response);
                    // checkedInput();
                    $("#modalSearchProduct .modal-body").removeClass(
                        "overlay-spinner overflow-hidden"
                    );
                })
                .fail(function (error) {
                    $("#modalSearchProduct .modal-body").addClass(
                        "overlay-spinner overflow-hidden"
                    );
                });
        }, 2000);
    };

    //Get data of page
    let getDataOfPageProduct = () => {
        $(document).on(
            "click",
            "#modalSearchProduct .pagination li a",
            function (e) {
                e.preventDefault();
                let url = $(this).attr("href");
                let data = {
                    code: code_search,
                    name: name_search,
                };
                page = $(this).text();
                actionGetDataProduct(url, data);
            }
        );
    };

    //Search modal data
    let searchModalDataProduct = () => {
        $(".form-search-modal-product").on("submit", function (e) {
            e.preventDefault();
            code_search = $(this).find('input[name="code"]').val();
            name_search = $(this).find('input[name="name"]').val();

            let url = $(this).attr("action");
            let data = {
                code: code_search,
                name: name_search,
            };

            actionGetDataProduct(url, data);
            $('button[type="submit"]').prop("disabled", false);
        });
    };

    //Choose product
    let chooseDataProduct = () => {
        $(document).on(
            "click",
            '#modalSearchProduct input[name="product_id"]',
            function (e) {
                let checked = [];

                $("#modalSearchProduct")
                    .find('input[name="product_id"]')
                    .each(function (i) {
                        if ($(this).prop("checked")) {
                            checked.push($(this).val());
                        }
                    });

                if (sessionStorage.getItem("dataModalSearchPro") != null) {
                    let products = sessionStorage.getItem("dataModalSearchPro");
                    checked = checked.concat(JSON.parse(products));
                    checked = [...new Set(checked)];
                }

                if (!$(this).prop("checked")) {
                    let unchecked = $(this).val();

                    let checkedLast = [];

                    $.each(checked, function (i, item) {
                        if (Number(item) > Number(unchecked)) {
                            checkedLast.push(checked[i]);
                        }
                    });

                    checked = checkedLast;
                }

                $("#modalSearchProduct")
                    .find('input[name="product_id"]')
                    .each(function (i) {
                        if (
                            Math.min.apply(Math, checked) <= $(this).val() &&
                            Math.max.apply(Math, checked) >= $(this).val()
                        ) {
                            $(this).prop("checked", true);
                        } else {
                            $(this).prop("checked", false);
                        }
                    });

                sessionStorage.setItem(
                    "dataModalSearchPro",
                    JSON.stringify(checked)
                );
            }
        );
    };

    //Fill data product
    let fillDataProduct = () => {
        $("#btn-choose-product").on("click", function (e) {

            // let checked = JSON.parse(
            //     sessionStorage.getItem("dataModalSearchPro")
            // );
            // if (checked === null || checked.length === 0) return;
            // let productCode = [];
            // $(document)
            //     .find('#modalSearchProduct input[name="product_id"]')
            //     .each(function () {
            //         if (
            //             Math.min.apply(Math, checked) <= $(this).val() &&
            //             Math.max.apply(Math, checked) >= $(this).val()
            //         ) {
            //             productCode.push($(this).data("code"));
            //         }
            //     });

            // $('input[name="product_code_s"]').val(productCode[0]);
            // $('input[name="product_code_e"]').val(
            //     productCode.at(-1) == productCode[0] ? "" : productCode.at(-1)
            // );

            let dataProductChecked = {}
            $("#data-table-search-product")
                .find('input[name="product_id"]')
                .each(function () {
                    if ($(this).prop("checked")) {
                        dataProductChecked['name'] = $(this).data("name");
                        dataProductChecked['code'] = $(this).data("code");
                        radio = $(this).val();
                    }
                });

            if (dataProductChecked) {
                $(".product-name").val(dataProductChecked.name);
                $(".product-code").val(dataProductChecked.code);
            }
        });
    };

    //Checked input
    let checkedInput = () => {
        if (sessionStorage.getItem("dataModalSearchPro") === null) return;
        let products = JSON.parse(sessionStorage.getItem("dataModalSearchPro"));
        $(document)
            .find('#modalSearchProduct input[name="product_id"]')
            .each(function () {
                if (
                    Math.min.apply(Math, products) <= $(this).val() &&
                    Math.max.apply(Math, products) >= $(this).val()
                ) {
                    $(this).prop("checked", true);
                } else {
                    $(this).prop("checked", false);
                }
            });
    };

    //Clear all
    let clearAll = () => {
        $("#clearAllPro").on("click", function () {
            name_search  = '';
            code_search = '';
            radio = 0;
            page = 0;
            $(document).find("#data-table-search-product tbody tr").remove();
            $(document).find("#modalSearchProduct .pagination").remove();
            $(document)
                .find("#modalSearchProduct .modal-body")
                .removeClass("modal-overflow");
        });
    };
    return {
        _: function () {
            modalSearchProduct();
            getDataOfPageProduct();
            // chooseDataProduct();
            searchModalDataProduct();
            fillDataProduct();
            clearAll();
        },
    };
})();

jQuery(function ($) {
    MODAL_SEARCH_AGENT._();
    MODAL_SEARCH_PRODUCT._();
});
