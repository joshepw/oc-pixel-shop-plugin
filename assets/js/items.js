jQuery(document).ready(function($) {
	$('#Form-field-Item-description-group').closest('.tab-pane').css('padding', '0px');

	$("#value-price").keyup(function (e) {
		computePriceWithTax();
	});

	$("#Form-field-Item-tax-group").find('input[type="checkbox"]').change(function (e) {
		computePriceWithTax();
	});

	$('#Form-field-Item-is_with_variants').change(function(event) {
		checkVariantActive($(this));
	});

	checkVariantActive($('#Form-field-Item-is_with_variants'));
});

function checkVariantActive(el){
	if(el.prop('checked'))
		$('.variants-main-container').removeClass('inactive');
	else
		$('.variants-main-container').addClass('inactive');
}

function computePriceWithTax(){
	var tax = 0.00;
	var basePrice = parseFloat($("#value-price").val().replace(',', '')) || 0.00;

	$("#Form-field-Item-tax-group").find('input[type="checkbox"]').each(function(index, el) {
		if($(this).prop('checked'))
			tax += ((parseInt($(this).val()) / 100) * basePrice);
	});

	var price = (basePrice + tax) || 0.00;

	$("#Retail_price_with_tax").html(formatMoney(price));
}

function formatMoney(n, c, d, t) {
	var c = isNaN(c = Math.abs(c)) ? 2 : c,
	d = d == undefined ? "." : d,
	t = t == undefined ? "," : t,
	s = n < 0 ? "-" : "",
	i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
	j = (j = i.length) > 3 ? j % 3 : 0;

	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};