var CartButton = {};

CartButton.interval = null;
CartButton.intervalTimeOut = 5000;

CartButton.onSuccess = function(data) {
    if(data.itemAdded) CartButton.show();
}

CartButton.show = function(text){
	$('.shop__cart-button').removeClass('empty');

	$('.shop__cart-button').addClass('notify');
	CartButton.interval = setTimeout(function(){
		$('.shop__cart-button').removeClass('notify');
	}, CartButton.intervalTimeOut);
};

jQuery(document).ready(function($) {
	$('.shop__cart-button').addClass('active');
});