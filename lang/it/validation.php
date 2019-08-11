<?php

return [
    'cvv' => 'Il codice CVV non è valido',
    'ccn' => 'Il numero della carta non è valido.',
    'ccexp' => 'La data di scadenza della carta non è valida.',
    'money' => 'Il formato dell\':attribute non è valido.',
    'amount' => 'L\':attribute deve essere maggiore di 1.00 o contenere un formato valido.',

    'cc_name.required_if' => 'Il campo :attribute è obbligatorio.',
    'cc_number.required_if' => 'Il campo :attribute è obbligatorio.',
    'cc_exp.required_if' => 'Il campo :attribute è obbligatorio.',
    'cc_cvv.required_if' => 'Il campo :attribute è obbligatorio.',

    'billing_address.first_line.required_without' => 'Il campo :attribute è obbligatorio.',
    'billing_address.country.required_without' => 'Il campo :attribute è obbligatorio.',
    'billing_address.state.required_without' => 'Il campo :attribute è obbligatorio.',
    'billing_address.city.required_without' => 'Il campo :attribute è obbligatorio.',

    'gateway.required' => 'Scegli prima un metodo di pagamento.',

    'jquery' => [
        'required' => 'Informazione obbligatoria',
        'email' => 'Digitare una mail valida',
        'maxlength' => 'Immettere non più di {0} caratteri',
        'minlength' => 'Inserisci almeno {0} caratteri',
    ],

    'values' => [
        'gateway' => [
            'cc' => 'Carta di credito'
        ]
    ]
];
