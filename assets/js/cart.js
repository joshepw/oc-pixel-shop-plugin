var Cart = {};

Cart.zipCodes = { "AD": "AA999", "AE": "", "AF": "9999", "AG": "99999", "AI": "AA-9999", "AL": "9999", "AM": "9999", "AN": "", "AO": "", "AQ": "9999", "AR": "A9999AAA", "AS": "99999", "AT": "9999", "AU": "9999", "AW": "9999 AA", "AX": "99999", "AZ": "AA 9999", "BA": "99999", "BB": "AA99999", "BD": "9999", "BE": "9999", "BF": "", "BG": "9999", "BH": "9999", "BI": "", "BJ": "", "BL": "99999", "BM": "AA 99", "BN": "AA9999", "BO": "", "BQ": "9999 AA", "BR": "99999-999", "BS": "", "BT": "99999", "BV": "", "BW": "", "BY": "999999", "BZ": "", "CA": "A9A 9A9", "CC": "9999 A9", "CD": "", "CF": "", "CG": "", "CH": "9999", "CI": "", "CK": "", "CL": "9999999", "CM": "", "CN": "999999", "CO": "999999", "CR": "99999", "CU": "99999", "CV": "9999", "CW": "9999 AA", "CX": "9999", "CY": "9999", "CZ": "999 99", "DE": "99999", "DJ": "", "DK": "9999", "DM": "", "DO": "99999", "DZ": "99999", "EC": "999999", "EE": "99999", "EG": "99999", "EH": "99999", "ER": "", "ES": "99999", "ET": "9999", "FI": "99999", "FJ": "", "FK": "AAAA 9AA", "FM": "99999", "FO": "999", "FR": "99999", "GA": "", "GB": "AA9 9AA", "GD": "", "GE": "9999", "GF": "99999", "GG": "AA9 9AA", "GH": "", "GI": "AA99 9AA", "GL": "9999", "GM": "", "GN": "999", "GP": "99999", "GQ": "", "GR": "999 99", "GS": "AAAA 9AA", "GT": "99999", "GU": "99999", "GW": "9999", "GY": "", "HK": "999999", "HM": "9999", "HN": "", "HR": "99999", "HT": "9999", "HU": "9999", "ID": "99999", "IE": "", "IL": "9999999", "IM": "AA99 9AA", "IN": "999999", "IO": "AA9A 9AA", "IQ": "99999", "IR": "99999", "IS": "999", "IT": "99999", "JE": "AA9 9AA", "JM": "AAAAA99", "JO": "99999", "JP": "999-9999", "KE": "99999", "KG": "999999", "KH": "99999", "KI": "", "KM": "", "KN": "", "KP": "", "KR": "99999", "KW": "99999", "KY": "AA9-9999", "KZ": "999999", "LA": "99999", "LB": "9999", "LC": "", "LI": "9999", "LK": "99999", "LR": "9999", "LS": "999", "LT": "99999", "LU": "9999", "LV": "AA-9999", "LY": "99999", "MA": "99999", "MC": "99999", "MD": "9999", "ME": "99999", "MF": "99999", "MG": "999", "MH": "99999", "MK": "9999", "ML": "", "MM": "99999", "MN": "99999", "MO": "999999", "MP": "99999", "MQ": "99999", "MR": "", "MS": "AAA9999", "MT": "AAA 9999", "MU": "A9999", "MV": "99999", "MW": "", "MX": "99999", "MY": "99999", "MZ": "9999", "NA": "", "NC": "99999", "NE": "9999", "NF": "9999", "NG": "999999", "NI": "99999", "NL": "9999 AA", "NO": "9999", "NP": "99999", "NR": "", "NU": "", "NZ": "9999", "OM": "999", "PA": "", "PE": "99999", "PF": "99999", "PG": "999", "PH": "9999", "PK": "99999", "PL": "99-999", "PM": "99999", "PN": "AAA9 9AA", "PR": "99999", "PS": "999-999", "PT": "9999-999", "PW": "99999", "PY": "9999", "QA": "", "RE": "99999", "RO": "999999", "RS": "99999", "RU": "999999", "RW": "", "SA": "99999-9999", "SB": "", "SC": "", "SD": "99999", "SE": "999 99", "SG": "999999", "SH": "AAAA 9AA", "SI": "9999", "SJ": "9999", "SK": "999 99", "SL": "", "SM": "99999", "SN": "99999", "SO": "", "SR": "", "SS": "99999", "ST": "", "SV": "9999", "SX": "9999 AA", "SY": "", "SZ": "A999", "TC": "AAAA 9AA", "TD": "99999", "TF": "99999", "TG": "", "TH": "99999", "TJ": "999", "TK": "", "TL": "", "TM": "999999", "TN": "9999", "TO": "", "TR": "99999", "TT": "", "TV": "", "TW": "99999", "TZ": "", "UA": "99999", "UG": "", "US": "99999-9999", "UY": "99999", "UZ": "999999", "VA": "999", "VC": "AA9999", "VE": "9999", "VG": "AA9999", "VI": "99999", "VN": "999999", "VU": "", "WF": "99999", "WS": "99999", "XK": "99999", "YE": "", "YT": "99999", "ZA": "9999", "ZM": "99999", "ZW": "" };

Cart.setShippingCountryOnSelect = function (data) {
    if (!data.code)
        return;

    Cart.evalAddressSelect(data.code, 'shipping');
};

Cart.setBillingCountryOnSelect = function (data) {
    if (!data.code)
        return;

    Cart.evalAddressSelect(data.code, 'billing');
};

Cart.evalAddressSelect = function (countryCode, type) {
    if (countryCode && Cart.zipCodes[countryCode]) {
        $('.col-' + type).removeClass('col-md-4').addClass('col-md-3');
        $('.col-' + type + '-zip').fadeIn(300);
        $('.col-' + type + '-zip').find('input').mask(Cart.zipCodes[countryCode]);
        $('.col-' + type + '-zip').find('input').prop('required', true);
        $('[name="' + type + '_zip_required"]').val('required');
    } else {
        $('.col-' + type).removeClass('col-md-3').addClass('col-md-4');
        $('.col-' + type + '-zip').hide();
        $('.col-' + type + '-zip').find('input').prop('required', false);
        $('[name="' + type + '_zip_required"]').val('');
    }
}

Cart.checkSameAddress = function (el) {
    if (el.prop('checked')) {
        $('.shop__billing-elements').find('input,select').prop('disabled', true);
        $('.shop__billing-elements').hide();
    }
    else {
        $('.shop__billing-elements').find('input,select').prop('disabled', false);
        $('.shop__billing-elements').fadeIn();
    }
}

Cart.evalCCFields = function () {
    $('[name="cc_exp"]').mask('00 / 00');
    $('[name="cc_cvv"]').mask('000');

    $('[name="cc_number"]').keyup(function (event) {
        Cart.testCardNumber($(this));
    }).change(function (event) {
        Cart.testCardNumber($(this));
    }).on('paste', function () {
        setTimeout(function () {
            Cart.testCardNumber($(this));
        }, 100);
    }).mask('0000 0000 0000 0000');

    $('[name="cc_name"]').mask('P', {
        translation: {
            P: {
                pattern: /[A-Za-z ]/,
                recursive: true
            }
        }
    });
}

Cart.testCardNumber = function (el) {
    var number = el.val();
    var brand = Cart.cardTypeFromNumber(number);
    var cvcEl = $('[name="cc_cvv"]');
    var lastBrand = el.attr('data-type');

    if (lastBrand != brand) {
        if (brand == 'amex') {
            el.mask('0000 000000 00000');
            el.attr('maxlength', 17);

            cvcEl.mask('0000');
            cvcEl.attr('maxlength', 4);
            cvcEl.attr('placeholder', '••••');
        } else if (brand == 'diners') {
            el.mask('0000 0000 0000 00');
            el.attr('maxlength', 17);

            cvcEl.mask('000');
            cvcEl.attr('maxlength', 3);
            cvcEl.attr('placeholder', '•••');
        } else {
            el.mask('0000 0000 0000 0000');
            el.attr('maxlength', 19);

            cvcEl.mask('000');
            cvcEl.attr('maxlength', 3);
            cvcEl.attr('placeholder', '•••');
        }

        el.attr('data-type', brand);
    }
}

Cart.cardTypeFromNumber = function (number) {
    // Diners - Carte Blanche
    re = new RegExp("^30[0-5]");
    if (number.match(re) != null)
        return "diners";

    // Diners
    re = new RegExp("^(30[6-9]|36|38)");
    if (number.match(re) != null)
        return "diners";

    // JCB
    re = new RegExp("^35(2[89]|[3-8][0-9])");
    if (number.match(re) != null)
        return "jcb";

    // AMEX
    re = new RegExp("^3[47]");
    if (number.match(re) != null)
        return "amex";

    // Visa Electron
    re = new RegExp("^(4026|417500|4508|4844|491(3|7))");
    if (number.match(re) != null)
        return "visa";

    // Visa
    var re = new RegExp("^4");
    if (number.match(re) != null)
        return "visa";

    // Mastercard
    re = new RegExp("^(5[1-5]|2(22[1-9]|2[3-9][0-9]|[3-6][0-9]{2}|7[0-1][0-9]|720))");
    if (number.match(re) != null)
        return "mastercard";

    // Discover
    re = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
    if (number.match(re) != null)
        return "discover";

    return "";
};

Cart.focusToInvalidFields = function (data) {
    $('[data-validate-for]').html('').removeClass('visible');

    if(data.responseJSON && data.responseJSON.X_OCTOBER_ERROR_FIELDS){
        $.each(data.responseJSON.X_OCTOBER_ERROR_FIELDS, function (i, val) { 
            try {
                console.log('Validate ' + i + ': '+ val[0]);
                $('[data-validate-for="'+i+'"]').html(val[0]).addClass('visible');
            } catch (error) {
                console.log('Error to validate')
            }
        });
    }

    var firstField = $('[data-validate-for].visible').first();

    if (!firstField)
        return;

    var index = firstField.closest('.--body').data('index');
    console.log('index', index);
    $(".shop__steps").steps("setStep", index);
};

jQuery(document).ready(function ($) {

    setTimeout(function (){
        if ($('[name="shipping_address[country]"]').val())
        Cart.evalAddressSelect($('[name="shipping_address[country]"]').val(), 'shipping');

        if ($('[name="billing_address[country]"]').val())
            Cart.evalAddressSelect($('[name="billing_address[country]"]').val(), 'billing');
    }, 100);

    if ($('#same-address').prop('checked'))
        Cart.checkSameAddress($('#same-address'));

    Cart.evalCCFields();

    $('#shop__cart-steps form').validate({
        errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            name = name.match(/[^\]\[.]+/g);
            name = name.join('.');

            $('[data-validate-for="' + name + '"]')
                .html(error.text())
                .addClass('visible');

            console.log(name);
        }
    });

    $(".shop__steps").steps({
        headerTag: "h3",
        bodyTag: ".step-content",
        titleTemplate: "#title#",
        transitionEffect: "fade",
        enablePagination: false,
        autoFocus: true,
        onStepChanging: function (event, currentIndex, newIndex) {
            if (currentIndex > newIndex)
                return true;

            $('#shop__cart-steps form').validate().settings.ignore = ":disabled,:hidden";
            return $('#shop__cart-steps form').valid();
        },
        onFinishing: function (event, currentIndex) {
            $('#shop__cart-steps form').validate().settings.ignore = ":disabled";
            return $('#shop__cart-steps form').valid();
        },
        onStepChanged: function (event, currentIndex, priorIndex) {
            if(currentIndex == 0){
                $('.btn-prev').hide();
                $('.btn-back-shopping').fadeIn();
            }else{
                $('.btn-prev').fadeIn();
                $('.btn-back-shopping').hide();
            }

            if ($(this).steps("stepCount") === (currentIndex + 1)) {
                $('#shop__cart-steps form').validate().settings.ignore = ":disabled";
                $('.btn-finish').fadeIn();
                $('.btn-next').hide();
            } else {
                $('[data-validate-for]').html('').removeClass('visible');
                $('.btn-finish').hide();
                $('.btn-next').fadeIn();
            }
        }
    });

    $('.btn-prev').click(function (e) {
        e.preventDefault();
        $(".shop__steps").steps("previous");
    });

    $('.btn-next').click(function (e) {
        e.preventDefault();
        $(".shop__steps").steps("next");
    });
});
