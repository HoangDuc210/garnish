var SEARCHPOST = (function() {
    var addressInput = $('input[name="address"]');
    var postCode = $('input[name="post_code"]');
    var searchPost = function() {
        if (postCode.length > 0) {
            postCode.keyup(function(){
                let codeVal = postCode.val();
                if (validate(codeVal)){
                    callApi(codeVal);
                };
                addressInput.val('');
                return false;
            });
        }
    }

    let validate = function(codeVal) {
        let reg = /^[0-9]{3}-[0-9]{4}$/;
        if (!reg.test(codeVal)){
            return false;
        }
        return true;
    }

    let callApi = function(codeVal) {
        $.ajax({
            url: 'https://zipcloud.ibsnet.co.jp/api/search?zipcode=' + codeVal,
            type: 'GET',
            dataType: 'jsonp',
        })
        .done(function(data) {
            if(!data.results)
            {
                addressInput.val('');
            }else{
                let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                addressInput.val(address);
            }
        });
    }

    return {
        _: function() {
            searchPost();
        }
    };
})();
jQuery(document).ready(function($) {
    SEARCHPOST._();
});