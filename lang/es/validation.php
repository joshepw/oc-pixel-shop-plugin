<?php 

return [
	'cvv' => 'El número de CVV no es válido.',
    'ccn' => 'El número de tarjeta no es válido.',
    'ccexp' => 'La fecha de vencimiento de la tarjeta no es válida.',
    'money' => 'El formato de :attribute no es válido.',
    'amount' => 'El :attribute debe ser mayor que 1.00 o contener un formato válido.',

    'cc_name.required_if' => 'El campo :attribute es obligatorio.',
    'cc_number.required_if' => 'El campo :attribute es obligatorio.',
    'cc_exp.required_if' => 'El campo :attribute es obligatorio.',
    'cc_cvv.required_if' => 'El campo :attribute es obligatorio.',

    'billing_address.first_line.required_without' => 'El campo :attribute es obligatorio.',
    'billing_address.country.required_without' => 'El campo :attribute es obligatorio.',
    'billing_address.state.required_without' => 'El campo :attribute es obligatorio.',
    'billing_address.city.required_without' => 'El campo :attribute es obligatorio.',

    'gateway.required' => 'Por favor, elija un método de pago primero',

    'jquery' => [
        'required' => 'Campo requerido',
        'email' => 'Introduzca una dirección de correo electrónico válida',
        'maxlength' => 'Ingrese no más de {0} caracteres',
        'minlength' => 'Ingrese al menos {0} caracteres',
    ],

    'values' => [
    	'gateway' => [
    		'cc' => 'tarjeta de crédito'
    	]
    ]
];

 ?>