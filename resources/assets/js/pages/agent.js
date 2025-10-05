var AGENT = (function () {
    let agentParentCheckBox = $('#check-agent-id');
    let agentNameInput = $('input[name="name"]');
    let agentSelect = $('select[name="agent[id]"]');
    let collectionRateInput = $('input[name="collection_rate"]');

    //Check agent parent
    var checkAgentParent = function () {
        agentParentCheckBox.on('change', function(){
            let agentName = agentNameInput.val();
            if ($(this).prop('checked') && agentName !== "") {
                let data = {
                    id: 0,
                    name: agentName,
                }

                agentSelect.removeClass('agent-select');
                agentSelect.select2("destroy");
                agentSelect.empty();
                return setDataSelect2(data, agentSelect);
            }

            agentSelect.val('').trigger("change");
            agentSelect.addClass('agent-select');
            return GLOBAL_CONFIG.selectAgent();
        });
    }

    //Change input name agent
    var changeNameAgentInput = function () {
        agentNameInput.on('keyup', function(){
            let agentName = agentNameInput.val();
            if (agentParentCheckBox.prop('checked') && agentName !== "") {
                let data = {
                    id: 0,
                    name: agentName
                }

                return setDataSelect2(data, agentSelect);
            }

            agentSelect.val('').trigger("change");
            return GLOBAL_CONFIG.selectAgent();
        });
    }

    let setDataSelect2 = function (data = {}, element) {
        let newOption = new Option(
            data.name,
            data.id,
            true,
            true
        );
        return element.append(newOption).trigger("change");
    }

    //Check number collection rate
    var validateCollectionRate = function () {
        collectionRateInput.on("keydown", function (){
            if (
                (!GLOBAL_CONFIG.unavailableKeyCode() && event.keyCode != 190) ||
                event.keyCode == 188
            ) {
                event.preventDefault();
            }
        });
    }

    //Destroy select when input checked parent
    var destroySelect2WhenInputChecked = function () {
        if (agentParentCheckBox.prop("checked")) {
            agentSelect.val('').trigger("change");
            $('select[name="agent[id]').removeClass('agent-select');
            return agentSelect.empty();
        }
    }

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
        Inputmask(options).mask($("input[name='tax_rate']"));
    };

    return {
        _: function () {
            checkAgentParent();
            changeNameAgentInput();
            validateCollectionRate();
            destroySelect2WhenInputChecked();
            inputMaskDecimal();
        },
        setDataSelect2(){
            if (!agentSelect.val()) {
                let data = {
                    id: 0,
                    name: agentNameInput.val(),
                };
                return setDataSelect2(data, agentSelect);
            }
        },
    };
})();

jQuery(function () {
    AGENT._();
    AGENT.setDataSelect2();
});