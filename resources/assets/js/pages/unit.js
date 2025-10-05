window.UNIT = (function () {
    let URL_UNIT_AJAX = "/units/search-ajax";
    let placeholder_select2 = "単位";
    let fillDataUnit = (element, value, id) => {
        if (!id || id == 0) {
            element.find('option[value="0"]').remove();
            let newOption = new Option(placeholder_select2, 0, true, true);
            element.append(newOption).trigger("change");
            element.find('option:selected').attr('selected', true).attr('hidden', true).attr('disabled', true);
        }else{
            element.find('option[value="'+ id +'"]').prop('selected', true);
            element.find('option[value="0"]').prop('selected', false);
        }

    }
    return {
        selectUnit: () => {
            $(document).find(".unit-select").each(function () {
                $(this).select2({
                    placeholder: placeholder_select2,
                    language: "ja",
                    theme: "bootstrap4",
                    allowClear: false,
                    ajax: {
                        url: URL_UNIT_AJAX,
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
                            var res = data.results.data.map(function (item) {
                                return {
                                    id: item.id,
                                    text: item.name,
                                };
                            });
                            return {
                                results: res,
                            };
                        },
                    },
                });
            });
        },
        selectedUnit: () => {
            $(document).find('.unit-select').each(function () {
                let value = $(this).data("name") ?? placeholder_select2;
                let id = $(this).data("id") ?? 0;
                fillDataUnit($(this), value, id);
            });
        },
        fillDataUnit: (element, value, id) => {
            return fillDataUnit(element, value, id);
        },
        init: function () {
            setTimeout(() => {
                // this.selectUnit();
                this.selectedUnit();
            }, 100);
        },
    };
})();

jQuery(function () {
    UNIT.init();
});
