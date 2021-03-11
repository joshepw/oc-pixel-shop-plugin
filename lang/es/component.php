<?php

return [
	'user' => [
		'sign_in' => 'Ir a Iniciar Sesión',
		'register' => 'Registrarse para una cuenta',
		'orders' => 'Historial de pedidos',
		'favorites' => 'Mis favoritos',
		'account' => 'Detalles de la cuenta',
		'logout' => 'Cerrar sesión',
		'sign_in_button' => 'Iniciar sesión',
		'register_button' => 'Registrarse',
		'save_details' => 'Guardar detalles',
		'desactivate_account' => 'Desactivar cuenta',
		'desactivate_account_question' => '¿Desactiva tu cuenta?',
		'desactivate_account_comment' => 'Se deshabilitará su cuenta y se eliminarán sus datos del sitio. Puede reactivar su cuenta en cualquier momento iniciando sesión nuevamente.',
		'desactivate_account_password' => 'Para continuar, por favor ingrese su contraseña:',
		'desactivate_account_confirm' => 'Confirmar Desactivar Cuenta',
		'desactivate_account_cancel' => 'Cambié de opinión',
		'is_activated' => 'Su dirección de correo electrónico aún no ha sido verificada.',
		'is_activated_comment' => 'Debe verificar su cuenta de lo contrario puede ser eliminado. Por favor revise su correo electrónico para verificar.',
		'recovery' => 'Recuperar contraseña',
		'recovery_button' => 'Enviar',
		'recovery_password' => 'Olvide mi contraseña'
	],
	'order' => [
		'not_confirmed' => 'SU PEDIDO NO ESTÁ CONFIRMADO',
    	'not_confirmed_comment' => 'Esta transacción ha sido alterada o no es válida.',
    	'confirmed' => 'SU PEDIDO ESTÁ CONFIRMADO',
    	'confirmed_comment' => 'Se ha enviado un correo electrónico a su dirección de correo electrónico :email.',
    	'from' => 'Orden de',
    	'to' => 'Orden para',
	],
	'cart' => [
		'notify_title' => 'Producto añadido al carrito',
        'notify_description' => 'Haga clic aquí para pagar',
        'order_summary' => 'Resumen del pedido',
        'payment_method_notes' => 'Notas del método de pago',
        'your_cart' => 'Su carrito',
        'cart_empty' => 'El carrito esta vacío',
        'empty_the_cart' => 'Vaciar el carro',
        'redeem' => 'Reclamar',
        'continue_shopping' => 'Seguir comprando',
        'promo_code' => 'Código Promocional',
        'customer_information' => 'Información del cliente',
        'choose' => 'Elija...',
        'shipping_address' => 'Dirección de Envío',
        'billing_address' => 'Dirección de Facturación',
        'optional' => 'Opcional',
        'is_ship_same_bill' => 'La dirección de facturación es la misma que la dirección de envío',
        'is_save_for_later' => 'Guarda esta información para la próxima vez',
        'place_order' => 'Realizar pedido',
        'bank_transfer_comment' => 'Por favor transfiera la cantidad total (:total) a nuestra cuenta bancaria.',
        'cash_on_delivery_comment' => 'Usted paga por la mercancía a la entrega.',
        'paypal_comment' => 'Cuando haga clic en <b>"Realizar pedido"</b>, se lo llevará al sitio web de <b>PayPal</b>.',
        'pixelpay_comment' => 'En la página siguiente, su pago se procesa a través de <b style="color: #226DF6">PixelPay &reg;</b><br>No guardamos información de la tarjeta. La transacción es <b style="color: #226DF6">encriptada y segura</b>',

        'name_card' => 'Nombre en la tarjeta',
        'card_number' => 'Numero de la tarjeta',
        'expiration' => 'Fecha de expiración',
        'card_accept' => 'Aceptamos estas tarjetas de crédito.',
        'full_name' => 'Nombre completo como aparece en la tarjeta',
        'filter_by_category' => 'Filtrar por Categoría',
        'no_results' => 'No hay resultados para mostrar.',
        'return' => 'Regresar',

        'customer_information' => 'Información del cliente',
        'shipping_method' => 'Método de envío',
        'complete_order' => 'Finalizar orden',
        'payment_method' => 'Metodo de Pago',
        'no_gateway_support' => 'Esta tienda no admite métodos de pago en este país (:country).'
    ],
    "payment" => [
        "request_error" => "Error al enviar los datos a PixelPay",
    ],
    'default' => [
        'name'                  =>      'Nombre',
        'email'                 =>      'Correo Electrónico',
        'password'              =>      'Contraseña',
        'password_confirmation' =>      'Confirmar Contraseña'
    ]
];

?>
