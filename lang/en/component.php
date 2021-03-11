<?php

return [
	'user' => [
		'sign_in' => 'Returning Customer',
		'register' => 'Register for an account',
		'orders' => 'Order History',
		'favorites' => 'My Favorites',
		'account' => 'Account Details',
		'logout' => 'Logout',
		'sign_in_button' => 'Sign in',
		'register_button' => 'Register',
		'save_details' => 'Save Details',
		'desactivate_account' => 'Deactivate account',
		'desactivate_account_question' => 'Deactivate your account?',
		'desactivate_account_comment' => 'Your account will be disabled and your details removed from the site. You can reactivate your account any time by signing back in.',
		'desactivate_account_password' => 'To continue, please enter your password:',
		'desactivate_account_confirm' => 'Confirm Deactivate Account',
		'desactivate_account_cancel' => 'I changed my mind',
		'is_activated' => 'Your email address has not yet been verified.',
        'is_activated_comment' => 'You should verify your account otherwise it may be deleted. Please check your email to verify.',
        'recovery' => 'Recover password',
        'recovery_password' => 'Forgot your password?',
        'recovery_button' => 'Recover password',
	],
	'order' => [
		'not_confirmed' => 'YOUR ORDER IS NOT CONFIRMED',
    	'not_confirmed_comment' => 'This transaction has been altered or is invalid.',
    	'confirmed' => 'YOUR ORDER IS CONFIRMED',
    	'confirmed_comment' => 'An email has been sent to your mail address :email.',
    	'from' => 'Order from',
    	'to' => 'Order to',
	],
	'cart' => [
		'notify_title' => 'Product added to the cart',
        'notify_description' => 'Click here to checkout',
        'order_summary' => 'Order Summary',
        'payment_method_notes' => 'Payment method notes',
        'your_cart' => 'Your cart',
        'cart_empty' => 'Cart is empty',
        'empty_the_cart' => 'Empty the cart',
        'redeem' => 'Redeem',
        'continue_shopping' => 'Continue shopping',
        'promo_code' => 'Promo code',
        'customer_information' => 'Customer information',
        'choose' => 'Choose...',
        'shipping_address' => 'Shipping address',
        'billing_address' => 'Billing address',
        'optional' => 'Optional',
        'is_ship_same_bill' => 'Billing address is the same as my shipping address',
        'is_save_for_later' => 'Save this information for next time',
        'place_order' => 'Place Order',
        'bank_transfer_comment' => 'Please transfer the total amount (:total) to our bank account.',
        'cash_on_delivery_comment' => 'You pay for the merchandise upon delivery.',
        'paypal_comment' => 'When you click <b>"Place Order"</b>, you will be taken to the <b>PayPal</b> website.',
        'pixelpay_comment' => 'On the next page your payment is processed through <b style="color: #226DF6">PixelPay&reg;</b><br>We do not store card data. The transaction is <b style="color: #226DF6">encrypted and secure</b>',

        'name_card' => 'Name on card',
        'full_name' => 'Full name as displayed on card',
        'card_number' => 'Credit card number',
        'expiration' => 'Expiration',
        'card_accept' => 'We accept these credit cards.',

        'filter_by_category' => 'Filter by Category',
        'no_results' => 'There are no results to show.',
        'return' => 'Return',

        'customer_information' => 'Customer Information',
        'shipping_method' => 'Shipping Method',
        'complete_order' => 'Complete Order',
        'payment_method' => 'Payment Method',
        'no_gateway_support' => 'This shop don\'t support and payment method in this country (:country).'
    ],
    "payment" => [
        "request_error" => "Error on sending data to PixelPay server",
    ],
    'default' => [
        'name'                  =>      'Name',
        'email'                 =>      'Email',
        'password'              =>      'Password',
        'password_confirmation' =>      'Confirm Password'
    ]
];

?>