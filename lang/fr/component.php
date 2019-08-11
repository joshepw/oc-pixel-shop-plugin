<?php

return [
	'user' => [
		'sign_in' => 'Client déjà inscrit',
		'register' => 'S\'inscrire pour ouvrir un compte',
		'orders' => 'Historique des commandes',
		'favorites' => 'Mes Favoris',
		'account' => 'Détails du compte',
		'logout' => 'Déconnexion',
		'sign_in_button' => 'S\'identifier',
		'register_button' => 'S\'inscrire',
		'save_details' => 'Enregistrer les détails',
		'desactivate_account' => 'Désactiver le compte',
		'desactivate_account_question' => 'Désactiver votre compte ?',
		'desactivate_account_comment' => 'Votre compte sera désactivé et vos coordonnées supprimées du site. Vous pouvez réactiver votre compte à tout moment en vous reconnectant.',
		'desactivate_account_password' => 'Pour continuer, veuillez entrer votre mot de passe:',
		'desactivate_account_confirm' => 'Confirmer désactivation du compte',
		'desactivate_account_cancel' => 'J\'ai changé d\'avis',
		'is_activated' => 'Votre adresse e-mail n\'a pas encore été vérifiée.',
		'is_activated_comment' => 'Vous devez vérifier votre compte, sinon il pourrait être supprimé. Veuillez vérifier vos courriels pour vous en assurer.',
	],
	'order' => [
		'not_confirmed' => 'VOTRE COMMANDE N\'EST PAS CONFIRMÉE',
    	'not_confirmed_comment' => 'Cette transaction a été modifiée ou est invalide.',
    	'confirmed' => 'VOTRE COMMANDE EST CONFIRMÉE',
    	'confirmed_comment' => 'Un email a été envoyé à votre adresse mail :email.',
    	'from' => 'Commande de',
    	'to' => 'Commande à',
	],
	'cart' => [
		'notify_title' => 'Produit ajouté au panier',
        'notify_description' => 'Cliquez ici pour passer à la caisse',
        'order_summary' => 'Résumé de la commande',
        'payment_method_notes' => 'Notes sur le mode de paiement',
        'your_cart' => 'Votre panier',
        'cart_empty' => 'Le panier est vide',
        'empty_the_cart' => 'Vider le panier',
        'redeem' => 'Échange',
        'continue_shopping' => 'Continuer mes achats',
        'promo_code' => 'Code promo',
        'customer_information' => 'Renseignements sur le client',
        'choose' => 'Choisir ...',
        'shipping_address' => 'Adresse de livraison',
        'billing_address' => 'Adresse de facturation',
        'optional' => 'Facultatif',
        'is_ship_same_bill' => 'L\'adresse de facturation est la même que mon adresse de livraison',
        'is_save_for_later' => 'Sauvegardez ces informations pour la prochaine fois.',
        'place_order' => 'Passer commande',
        'bank_transfer_comment' => 'Veuillez virer le montant total (:total) sur notre compte bancaire.',
        'cash_on_delivery_comment' => 'Vous payez la marchandise à la livraison.',
        'paypal_comment' => 'Lorsque vous cliquez sur <b>"Passer commande"</b>, vous serez dirigé vers le site Web <b>PayPal</b>.',
        'pixelpay_comment' => 'Sur la page suivante, votre paiement est traité par <b style="color : #226DF6">PixelPay&reg;</b><br>Nous ne stockons pas les données des cartes. La transaction est <b style="color : #226DF6">crypté et sécurisé </b>',

        'name_card' => 'Nom sur la carte',
        'card_number' => 'Numéro de carte de crédit',
        'expiration' => 'Expiration',
        'card_accept' => 'Nous acceptons ces cartes de crédit.',

        'filter_by_category' => 'Filtrer par catégorie',
        'no_results' => 'Il n\'y a pas de résultats à afficher.',
        'return' => 'Retour',

        'customer_information' => 'Renseignements sur le client',
        'shipping_method' => 'Méthode d\'expédition',
        'complete_order' => 'Terminer la commande',
        'payment_method' => 'Mode de paiement',
        'no_gateway_support' => 'Ce magasin ne prend pas en charge et mode de paiement dans ce pays (:country).'
    ],
    "payment" => [
        "request_error" => "Erreur d'envoi des données à PixelPay",
    ],
    'default' => [
        'name'                  =>      'Nom',
        'email'                 =>      'Email',
        'password'              =>      'Mot de passe',
        'password_confirmation' =>      'Confirmez le mot de passe'
    ]
];

?>
