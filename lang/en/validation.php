<?php 

return [
	'cvv' => 'The CVV number is invalid.',
    'ccn' => 'The card number is invalid.',
    'ccexp' => 'The expiration date of the card is invalid.',
    'money' => 'The :attribute format is invalid.',
    'amount' => 'The :attribute must be greater than 1.00 or contain a valid format.',

    'cc_name.required_if' => 'The :attribute field is required.',
    'cc_number.required_if' => 'The :attribute field is required.',
    'cc_exp.required_if' => 'The :attribute field is required.',
    'cc_cvv.required_if' => 'The :attribute field is required.',

    'billing_address.first_line.required_without' => 'The :attribute field is required.',
    'billing_address.country.required_without' => 'The :attribute field is required.',
    'billing_address.state.required_without' => 'The :attribute field is required.',
    'billing_address.city.required_without' => 'The :attribute field is required.',

    'gateway.required' => 'Please choose a payment method first',

    'jquery' => [
        'required' => 'Required field',
        'email' => 'Enter a valid email',
        'maxlength' => 'Enter no more than {0} characters',
        'minlength' => 'Enter at least {0} characters',
    ],

    'values' => [
    	'gateway' => [
    		'cc' => 'credit card'
		],
		'is_with_variants' => [
			'1' => 'on',
			'on' => 'on'
		]
    ]
];
