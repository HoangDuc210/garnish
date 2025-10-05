window.CHECKBOX_LIST = (function () {
    let selectPaginate = $('select[name="limit"]');
    let checkBoxTable = $("[data-table-checkbox]");
    let trThead = checkBoxTable.find("thead tr");
    let trTbody = checkBoxTable.find("tbody tr");
    let btnRemove = $('[data-btn-removes]');
    let checkboxIds = [];
    let idChecked = [];
    let unIdChecked = [];
    let nameSession = String(window.location.pathname).replace("/", "");

    var paginate = function () {
        if (selectPaginate.length === 0) {
            return false;
        }
        $(document).on("change", 'select[name="limit"]',function (event) {
            let limit = $(this).val();
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            urlParams.set('limit', limit);
            let origin = window.location.origin;
            let pathname = window.location.pathname + '?';
            return window.location.href = origin + pathname + urlParams;
        });
    };

    var tableCheckBox = function () {
        //Check empty table
        if (checkBoxTable.length === 0 || checkBoxTable.find('tbody tr').length === 0) {
            return false;
        }
        //Append input checkbox all
        let checked = sessionStorage.getItem('checkboxAll'+nameSession) == 1 ? 'checked' : '';
        let html = '<th class="text-center">';
        html +=
            '<input type="checkbox" name="checkbox_all" class="form-check-input" id="checkbox_all" ' + checked + '>';
        trThead.prepend(html);

        //Prepend input checkbox
        trTbody.each(function () {
            let id = $(this).data("id");
            let html = '<td class="text-center">';
            html +=
                '<input type="checkbox" name="id" value="' +
                id +
                '" class="form-check-input  checkbox-id"' + checked + '>';

            $(this).prepend(html);
        });

        checkboxAll();
        changeInput();
    };

    //Set data checkboxIds
    var setDataCheckboxIds = function () {
        trTbody.find('input[name="id"]').each(function () {
            checkboxIds.push($(this).val());
        })
        checkboxIds = Array.from(new Set(checkboxIds));
        return checkboxIds;
    }
    //Select all checkbox
    let checkboxAll = function () {
        $(document).on("click", "#checkbox_all", function (e) {
            sessionStorage.clear();
            let inputIds = checkBoxTable.find('.checkbox-id');
            if ($(this).prop("checked")) {
                sessionStorage.setItem("checkboxAll"+nameSession, 1);
                inputIds.each(function (){
                    $(this).prop("checked", true);
                    checkboxIds.push($(this).val());
                    idCheckedSession($(this).val());
                });
            } else {
                sessionStorage.clear();
                idChecked = [];
                unIdChecked = [];
                inputIds.each(function (){
                    $(this).prop("checked", false);
                    checkboxIds = [];
                });
            }

            idCheckedSessionLast();

            checkboxIds = Array.from(new Set(checkboxIds));
            return checkboxIds;
        });
    };

    // Change checkbox input
    let changeInput = function () {
        let inputIds = checkBoxTable.find('.checkbox-id');

        inputIds.on("change", function(){
            let val = $(this).val();
            sessionStorage.removeItem("checkboxAll"+nameSession);
            if (!$(this).prop("checked")) {
                $('input[name="checkbox_all"]').prop('checked', false);
                unIdCheckedSession(val);
                checkboxIds.splice(checkboxIds.indexOf(val), 1);
            }else{
                idCheckedSession(val);
                checkboxIds.push(val);
            }
            return idCheckedSessionLast();
        });
    }

    let idCheckedSession = (val) => {
        idChecked.push(val);
        idChecked = Array.from(new Set(idChecked));
        return sessionStorage.setItem("idChecked", JSON.stringify(idChecked));
    };

    let unIdCheckedSession = (val) => {
        unIdChecked.push(val);
        unIdChecked = Array.from(new Set(unIdChecked));
        return sessionStorage.setItem(
            "unIdChecked",
            JSON.stringify(unIdChecked)
        );
    };

    let idCheckedSessionLast = () => {
        let idChecked = JSON.parse(sessionStorage.getItem("idChecked"));
        let unIdChecked = JSON.parse(sessionStorage.getItem("unIdChecked"));
        idChecked = idChecked != null ? idChecked : [];
        unIdChecked = unIdChecked != null ? unIdChecked : [];

        let oldId = sessionStorage.getItem(nameSession);
        if (oldId != null) {
            idChecked = JSON.parse(oldId).concat(idChecked);
        }

        idChecked.map(function (id) {
            unIdChecked.map(function (unId) {
                if (id == unId) {
                    idChecked = Array.from(new Set(idChecked));
                    idChecked.splice(idChecked.indexOf(id), 1);
                }
            });
        });

        sessionStorage.removeItem("idChecked");
        sessionStorage.removeItem("unIdChecked");
        return sessionStorage.setItem(nameSession, JSON.stringify(idChecked));
    };

    //Removes item
    let removeItems = function () {
        if (btnRemove.length > 0) {
            btnRemove.on('click', function () {
                let ids = JSON.parse(sessionStorage.getItem(nameSession)) ?? [];
                let url = $(this).data('url');
                let data = {
                    ids: sessionStorage.getItem('checkboxAll'+nameSession) ? 'all' : ids
                }

                if (data.ids.length === 0) {
                    let content = '選択した伝票がありませんので選択してください。';
                    return GLOBAL_CONFIG.sweetAlert("", content, true)
                        .then(function (result) {
                            if (result.isConfirmed) {
                                btnRemove.attr('disabled', false);
                            }
                        });
                }

                GLOBAL_CONFIG.loadingCustom(true);

                let content = "選択した伝票を削除しますか？";
                GLOBAL_CONFIG.sweetAlert("", content).then(function (result) {
                    if (result.isConfirmed) {
                        GLOBAL_CONFIG.callAjax(url, "POST", data)
                        .done(function (response) {
                            GLOBAL_CONFIG.loadingCustom(false, {content: '成功を削除!'});
                            setTimeout(function () {
                                window.location.href = ""
                            }, 1000);
                        })
                        .fail(function (){
                            GLOBAL_CONFIG.loadingCustom(false, {content: '削除できませんでした!'});
                        });
                    }else{
                        GLOBAL_CONFIG.loadingCustom(false);
                    }
                });
            });
        }
    }

    //Set session checkbox
    let sessionCheckbox = () => {
        let ids = sessionStorage.getItem(nameSession);
        if (ids === null || ids.length == 0) return false;
        $(document)
            .find(".checkbox-id")
            .each(function () {
                let inputId = $(this);
                JSON.parse(ids).map(function (id) {
                    if (inputId.val() == id) {
                        inputId.prop("checked", true);
                    }
                });
            });

    }
    return {
        init: function () {
            paginate();
            tableCheckBox();
            setDataCheckboxIds();
            removeItems();
            sessionCheckbox();
        },
        dataTable: function () {
            return JSON.parse(sessionStorage.getItem(nameSession)) ?? [];
        }
    };
})();

jQuery(function ($) {
    CHECKBOX_LIST.init();
});
