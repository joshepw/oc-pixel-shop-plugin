<?php

return [
	'user' => [
		'sign_in' => 'Cliente già registrato? Effettua il login',
		'register' => 'Registrati per un account',
		'orders' => 'Storico Ordini',
		'favorites' => 'Preferiti',
		'account' => 'Dettagli Account',
		'logout' => 'Logout',
		'sign_in_button' => 'Log in',
		'register_button' => 'Registrati',
		'save_details' => 'Salva Dettagli',
		'desactivate_account' => 'Disattiva account',
		'desactivate_account_question' => 'Si vuole disattivare il proprio account?',
		'desactivate_account_comment' => 'Il tuo account sarà disabilitato e le tue informazioni personali saranno rimosse dal sito. Puoi ri-attivare il tuo account in qualsiasi momento registrandoti nuovamente.',
		'desactivate_account_password' => 'Per continuare, inserisci la tua password:',
		'desactivate_account_confirm' => 'Conferma la disattivazione del tuo account',
		'desactivate_account_cancel' => 'Ho cambiato idea',
		'is_activated' => 'Il tuo indirizzo email non è stato ancora confermato.',
		'is_activated_comment' => 'Devi verificare il tuo account altrimenti verrà eliminato. Controlla la tua email per verificare l\'account',
	],
	'order' => [
		'not_confirmed' => 'IL TUO ORDINE NON È STATO CONFERMATO',
    	'not_confirmed_comment' => 'Questa transazione è stata eliminata oppure non è valida.',
    	'confirmed' => 'IL TUO ORDINE È CONFERMATO',
    	'confirmed_comment' => 'Un\'email è stata inviata al tuo indirizzo :email.',
    	'from' => 'Ordine da',
    	'to' => 'Cliente',
	],
	'cart' => [
		'notify_title' => 'Prodotto aggiunto al carrello',
        'notify_description' => 'Clicca qui per procedere al checkout',
        'order_summary' => 'Riepilogo dell\'ordine',
        'payment_method_notes' => 'Modalità di pagamento',
        'your_cart' => 'Il tuo carrello',
        'cart_empty' => 'Il carrello è vuoto',
        'empty_the_cart' => 'Svuota il carrello',
        'redeem' => 'Riscatta', //da verificare
        'continue_shopping' => 'Continua gli acquisti',
        'promo_code' => 'Codice promo',
        'customer_information' => 'Informazioni cliente',
        'choose' => 'Scegli...',
        'shipping_address' => 'Indirizzo di spedizione',
        'billing_address' => 'Indirizzo di fatturazione',
        'optional' => 'Opzionale',
        'is_ship_same_bill' => 'L\'indirizzo di fatturazione è lo stesso del mio indirizzo di spedizione',
        'is_save_for_later' => 'Salva le informazioni inserite per futuri acquisti',
        'place_order' => 'Invia ordine',
        'bank_transfer_comment' => 'Pagamento dell\'intero importo (:total) tramite bonifico bancario anticipato.',
        'cash_on_delivery_comment' => 'Si paga per la merce al momento della consegna.',
        'paypal_comment' => 'Paga con <b>"Carta di Credito/Debito"</b>tramite il portale web di <b>PayPal</b>.',
        'pixelpay_comment' => 'Nella pagina successiva il pagamento viene elaborato tramite <b style="color: #226DF6">PixelPay&reg;</b><br>Noi non salveremo i dati della carta. La transazione sarà <b style="color: #226DF6">criptata e sicura</b>',

        'name_card' => 'Nome sulla carta',
        'card_number' => 'Numero carta di credito',
        'expiration' => 'Scadenza',
        'card_accept' => 'Carta di credito accettata.',

        'filter_by_category' => 'Filtra per categoria',
        'no_results' => 'Non ci sono risultati da mostrare.',
        'return' => 'Ritorna',

        'customer_information' => 'Informazioni cliente',
        'shipping_method' => 'Metodo di spedizione',
        'complete_order' => 'Completa ordine',
        'payment_method' => 'Metodo di pagamento',
        'no_gateway_support' => 'Questo negozio non supporta e metodi di pagamento in questo paese (:country).'
    ],
    "payment" => [
        "request_error" => "Errore durante l'invio dei dati a PixelPay",
    ],
    'default' => [
        'name'                  =>      'Nome',
        'email'                 =>      'Email',
        'password'              =>      'Password',
        'password_confirmation' =>      'Conferma Password'
    ]
];

?>
