<?php

return [
	'cvv' => 'Le numéro CVV n\'est pas valide.',
    'ccn' => 'Le numéro de carte n\'est pas valide.',
    'ccexp' => 'La date d\'expiration de la carte est invalide.',
    'money' => 'Le format :attribut n\'est pas valide.',
    'amount' => 'L\'attribut :doit être supérieur à 1.00 ou comporter un format valide.',

    'cc_name.required_if' => 'Le champ :attribut est obligatoire.',
    'cc_number.required_if' => 'Le champ :attribut est obligatoire.',
    'cc_exp.required_if' => 'Le champ :attribut est obligatoire.',
    'cc_cvv.required_if' => 'Le champ :attribut est obligatoire.',

    'billing_address.first_line.required_without' => 'Le champ :attribut est obligatoire.',
    'billing_address.country.required_without' => 'Le champ :attribut est obligatoire.',
    'billing_address.state.required_without' => 'Le champ :attribut est obligatoire.',
    'billing_address.city.required_without' => 'Le champ :attribut est obligatoire.',

    'gateway.required' => 'Veuillez d\'abord choisir un mode de paiement',

    'jquery' => [
        'required' => 'Champs requis',
        'email' => 'Entrer un email valide',
        'maxlength' => 'N\'entrez pas plus de {0} caractères',
        'minlength' => 'Entrez au moins {0} caractères',
    ],

    'values' => [
    	'gateway' => [
    		'cc' => 'carte bancaire'
    	]
    ]
];

 ?>