var PREV_NEXT_PAGE_URL = (function () {
    let nameSession = String(window.location.pathname)
        .replace("/", "")
        .replace(/[0-9]/g, "");
    let prevNextCheckbox = $("#checkboxNextPrevPage");
    let prevButton = $(".btn-prev-page");
    let nextButton = $(".btn-next-page");
    let url = prevNextCheckbox.data("url");
    //Checkbox option prev next page
    var handleCheckboxOption = function () {
        prevNextCheckbox.on("click", function () {
            sessionStorage.setItem('prev_next_page'+nameSession, $(this).prop('checked') ? 1 : 0);
            let data = {
                id: $(this).val(),
                prev_next_page: $(this).prop('checked') ? 'checked' : 'unchecked',
            };
            return actionCallAjax(url, data);
        });
    };
    //Load the next page
    let loadPrevPage = () => {
        let checked = sessionStorage.getItem('prev_next_page'+nameSession);
        prevNextCheckbox.prop('checked', checked == 1 ? true : false);
        let data = {
            id: prevNextCheckbox.val(),
            prev_next_page: checked == 1 ? 'checked' : 'unchecked',
        };
        return actionCallAjax(url, data);
    };
    //Action call ajax
    let actionCallAjax = (url, data) => {
        GLOBAL_CONFIG.callAjax(url, "POST", data)
            .done(function (response) {
                let prevPageUrl = response.result.prevPageUrl;
                let nextPageUrl = response.result.nextPageUrl;
                setTimeout(function () {
                    prevButton.attr("href", prevPageUrl);
                    nextButton.attr("href", nextPageUrl);
                    nextPageUrl
                        ? nextButton.removeClass("pe-none")
                        : nextButton.addClass("pe-none");
                    prevPageUrl
                        ? prevButton.removeClass("pe-none")
                        : prevButton.addClass("pe-none");
                }, 500);
            })
            .fail(function () {
                setTimeout(function () {
                    nextButton.removeClass("pe-none");
                    prevButton.removeClass("pe-none");
                }, 500);
            });
    };
    return {
        _: function () {
            handleCheckboxOption();
            loadPrevPage();
        },
    };
})();

jQuery(function () {
    PREV_NEXT_PAGE_URL._();
});
